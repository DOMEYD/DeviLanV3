<?
namespace UA\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use UA\BlogBundle\Entity\Articles;
use UA\BlogBundle\Form\ArticlesType;

class BlogController extends Controller {
	
	public function indexAction($page) {
		//Gerer si la page peut exister 
		if( $page < 1 ) {
			//Generation d'une erreur dans le cas contraire
			throw $this->createNotFoundException('Page inexistante (page = '.$page.')');
		}

		//Recuperation du EntityManager de Doctrine
		$em = $this->getDoctrine()->getManager();

		//Recuperation des articles
		$listeArticles = $em->getRepository('UABlogBundle:Articles')->findBy(array(), array('id' => 'DESC'));

		//Rendu de la page
		return $this->render('UABlogBundle:Blog:index.html.twig', array('page' => $page, 'Articles' => $listeArticles));
	}

	public function voirAction(Articles $Article) {
		$em = $this->getDoctrine()->getManager();
		//$Article = $em->find('UABlogBundle:Articles', $id);

		if($Article === null) {
			throw $this->createNotFoundException('L\'article '. $id .' n\'a pas été trouvé !');
		}

		$listeCom = $em->getRepository('UABlogBundle:Commentaires')->findAll();
		return $this->render('UABlogBundle:Blog:voir.html.twig', array('article'  => $Article, 'comment' => $listeCom));
	}

	public function addAction() {
		$AddArticle = new Articles;
		$AddArticle->setAuteur($this->getUser());
		$formError = '';
		$form = $this->createForm(new ArticlesType, $AddArticle);

		if ($this->getRequest()->getMethod() == 'POST') {
			$form->bind($this->getRequest());

			if ($form->isValid()) {
				//Enregistrement
				$em = $this->getDoctrine()->getManager();
				$em->persist($AddArticle);
				$em->flush();

				//on redirige vers l'Article
				return $this->redirect( $this->generateUrl('UAblog_voir', array('id' => $AddArticle->getId())) );
			}
			else {
				$formError = $this->get('validator')->validate($AddArticle);
			}
		}

		//Recuperation des 5 derniers Articles
		$em = $this->getDoctrine()->getManager();
		$Articles = $em->getRepository('UABlogBundle:Articles')->findBy(array(), array('id' => 'DESC'), 5);

		//Rendu de la page si aucun formulaire n'a ete soumis
		return $this->render('UABlogBundle:Blog:add.html.twig', array('form' => $form->createView(), 'articles' => $Articles, 'error' => $formError));
	}

	public function modifAction($article) {
		$em = $this->getDoctrine()->getManager();
		$modifArticle = $em->find('UABlogBundle:Articles', $article);
		$modifArticle->setAuteur('Moustako');
		$form = $this->createForm(new ArticlesType, $modifArticle);

		if ($this->getRequest()->getMethod() == 'POST') {
			$form->bind($this->getRequest());

			if ($form->isValid()) {
				//On enregistre
				$em = $this->getDoctrine()->getManager();
				$em->persist($AddArticle);
				$em->flush();

				//on redirige vers l'Article
				return $this->redirect( $this->generateUrl('UAblog_voir', array('id' => $AddArticle->getId())) );
			}
			/*else {
				return new Response('ERREUR !!!');
			}*/
		}

		//Recuperation des 5 derniers Articles
		$em = $this->getDoctrine()->getManager();
		$Articles = $em->getRepository('UABlogBundle:Articles')->findAll();

		//Rendu de la page si aucun formulaire n'a ete soumis
		return $this->render('UABlogBundle:Blog:add.html.twig', array('form' => $form->createView(), 'articles' => $Articles));
	}

	public function deleteAction($article) {
		$em = $this->getDoctrine()->getManager();
		$ArticleDeleting = $em->find('UABlogBundle:Articles', $article);

		//Creation d'un formulaire vide permettant la generation du token 
		$form = $this->createFormBuilder()->getForm();

		if($this->getRequest()->getMethod() == 'POST') {
			$form->bind($this->getRequest());

			if($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->remove($ArticleDeleting);
				$em->flush();

				//on redirige vers l'acceuil
				return $this->redirect($this->generateUrl('UAblog_default'));
			}
		}
		return $this->render('UABlogBundle:Blog:delete.html.twig', array('article' => $ArticleDeleting, 'form' => $form->createView()));
	}
}