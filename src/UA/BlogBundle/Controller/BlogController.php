<?
namespace UA\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
/* articles */
use UA\BlogBundle\Entity\Articles;
use UA\BlogBundle\Form\ArticlesType;
/* commentaires */
use UA\BlogBundle\Entity\Commentaires;
use UA\BlogBundle\Form\CommentairesType;


class BlogController extends Controller {
	
	/*
	 * PUBLIC
	 */
	public function indexAction($page) {
		//init
		$em = $this->getDoctrine()->getManager();

		$Articles = $em->getRepository('UABlogBundle:Articles')->findBy(array(), array('id' => 'DESC'));
		$nbrArticles = $em->getRepository('UABlogBundle:Articles')->getCount();

		return $this->render('UABlogBundle:Blog:index.html.twig', array('pageActive' => $page, 
																		'Articles' => $Articles,
																		'nbrPage' => ceil($nbrArticles/10)
																		));
	}

	public function voirAction($slug) {
		//init
		$em = $this->getDoctrine()->getManager();
		$newComs = new Commentaires();
		$form = $this->createForm(new CommentairesType, $newComs);
		$Article = $em->getRepository('UABlogBundle:Articles')->findOneBySlug($slug);

		//handling of new comment
		$request = $this->getRequest();
		if($request->getMethod() == 'POST') {
			$form->bind($request);
			if($form->isValid()) {
				$newComs->setAuthor($this->getUser());
				$Article->addComments($newComs);
				$em->persist($Article);
				$em->flush();

				$newComs = new Commentaires();
				$form = $this->createForm(new CommentairesType, $newComs);
			}
		}


		return $this->render('UABlogBundle:Blog:voir.html.twig', array(	'article'  => $Article,
																		'form' => $form->createView()));
	}

	/*
	 * ADMIN
	 */
	/**
	 * @Secure(roles="ROLE_BLOGGER")
	 */
	public function addAction($page) {
		//init
		$em = $this->getDoctrine()->getManager();
		$AddArticle = new Articles;
		$form = $this->createForm(new ArticlesType, $AddArticle);

		if ($this->getRequest()->getMethod() == 'POST') {
			$form->bind($this->getRequest());

			if ($form->isValid()) {
				$AddArticle->setAuthor($this->getUser());
				$em->persist($AddArticle);
				$em->flush();

				//redirect to the posted article
				return $this->redirect( $this->generateUrl('UAblog_voir', array('slug' => $AddArticle->getSlug())) );
			}
		}

		//recovery last 5 articles
		$Articles = $em->getRepository('UABlogBundle:Articles')->findBy(array(), array('id' => 'DESC'), 5, ($page-1)*5);
		$nbrArticles = $em->getRepository('UABlogBundle:Articles')->getCount();

		return $this->render('UABlogBundle:Admin:add.html.twig', array(	'form' => $form->createView(), 
																		'articles' => $Articles,
																		'page' => $page,
																		'nbrPage' => ceil($nbrArticles/5)));
	}

	/**
	 * @Secure(roles="ROLE_BLOGGER")
	 */
	public function modifAction($article) {
		$em = $this->getDoctrine()->getManager();
		$modifArticle = $em->find('UABlogBundle:Articles', $article);
		$form = $this->createForm(new ArticlesType, $modifArticle);

		if ($this->getRequest()->getMethod() == 'POST') {
			$form->bind($this->getRequest());

			if ($form->isValid()) {
				//add modify message
				$t = $this->get('translator');
				$modifArticle->setContent($modifArticle->getContent() . chr(13) . chr(10) . $t->trans('admin.modified.by') . $this->getUser());
				
				//save
				$em->persist($modifArticle);
				$em->flush();

				//redirect to article
				return $this->redirect( $this->generateUrl('UAblog_voir', array('slug' => $modifArticle->getSlug())) );
			}
		}

		//Rendu de la page si aucun formulaire n'a ete soumis
		return $this->render('UABlogBundle:Admin:add.html.twig', array(	'form' => $form->createView(),
																		'articles' => null));
	}

	/**
	 * @Secure(roles="ROLE_BLOGGER")
	 */
	public function deleteAction($article) {
		$em = $this->getDoctrine()->getManager();
		$ArticleDeleting = $em->find('UABlogBundle:Articles', $article);

		if(!$ArticleDeleting) {
			throw new createNotFoundException('article.NotFound.delete');
		}

		//Creation d'un formulaire vide permettant la generation du token 
		$form = $this->createFormBuilder()->getForm();

		if($this->getRequest()->getMethod() == 'POST') {
			$form->bind($this->getRequest());

			if($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->remove($ArticleDeleting);
				$em->flush();

				//on redirige vers l'acceuil
				return $this->redirect($this->generateUrl('UAblog_ajouter'));
			}
		}
		return $this->render('UABlogBundle:Admin:delete.html.twig', array('article' => $ArticleDeleting, 'form' => $form->createView()));
	}
}