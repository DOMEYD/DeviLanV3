<?
namespace UA\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminController extends Controller {

	/**
	 * @Secure(roles="ROLE_BLOGGER, ROLE_ARBITRE")
	 */
	public function indexAction() {
		return $this->render('UAUserBundle:Admin:index.html.twig');
	}

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function gestionAction($page = 1, $user = null) {
		//init
		$em = $this->getDoctrine()->getManager();
		if($user != null) {
			$Users = $em->getRepository('UAUserBundle:User')->findByUsername($user);
		}
		else {
			$Users = $em->getRepository('UAUserBundle:User')->findAll();
		}
		return $this->render('UAUserBundle:Admin:gestion.html.twig', array('users' => $Users,
																			'page' => $page));
	}

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function addAction() {
		$em = $this->getDoctrine()->getManager();
		$userManager = $this->container->get('fos_user.user_manager');
		$user = $userManager->createUser();
		$form = $this 	->createFormBuilder($user)
						->add('username', 'text')
						->add('plainPassword', 'password')
						->add('roles', 'choice', array( 'multiple' => true,
														'choices' => array( 'ROLE_SUPER_ADMIN' => $this->get('translator')->trans('admin.grade.admin'),
																			'ROLE_ADMIN' => $this->get('translator')->trans('admin.grade.modo'), 
																			'ROLE_ARBITRE' => $this->get('translator')->trans('admin.grade.referee'), 
																			'ROLE_BLOGGER' => $this->get('translator')->trans('admin.grade.blog'),
																			'ROLE_USER' => $this->get('translator')->trans('admin.grade.user'))))
						->getForm();
		if($this->getRequest()->getMethod() == 'POST') {
			$form->bind($this->getRequest());
			if($form->isValid()) {
				$user->setEmail( strtolower(preg_replace("#[^a-zA-Z]#", "", $user->getUsername())) . time() . "@devi.lan" );
				$user->setEnabled(true);
				$em->persist($user);
				$em->flush();

				$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('admin.add.success'));

				return $this->redirect($this->generateUrl('UA_user_add'));
			}
		}

		return $this->render('UAUserBundle:Admin:add.html.twig', array('form' => $form->createView()));
	}

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function gradeAction($id) {
		$em = $this->getDoctrine()->getManager();
		$user = $em->find('UAUserBundle:User', $id);

		$request = $this->getRequest();
		if($request->getMethod() == 'POST') {
			//Recuperation des donnees du formulaire
			$data = $request->request->get('form');
			$newRole = $roleDispo = array();

			//Recuperation des roles disponible
			foreach ($this->container->getParameter('security.role_hierarchy.roles') as $key => $role) {
				$roleDispo[] = $key; 
			}

			//Comparaison des donnees du formulaire et des roles disponibles
			foreach ($data['roles'] as $role) {
				if(in_array($role, $roleDispo)){
					$newRole[] = $role;
				}
			}
			
			//enregistrement
			$user->setRoles($newRole);
			$em->persist($user);
			$em->flush();

			return $this->redirect($this->generateUrl('UA_user_gestion'));
		}


		$form = $this	->createFormBuilder()
						->add('roles', 'choice', array( 'multiple' => true,
														'choices' => array( 'ROLE_SUPER_ADMIN' => $this->get('translator')->trans('admin.grade.admin'),
																			'ROLE_ADMIN' => $this->get('translator')->trans('admin.grade.modo'), 
																			'ROLE_ARBITRE' => $this->get('translator')->trans('admin.grade.referee'), 
																			'ROLE_BLOGGER' => $this->get('translator')->trans('admin.grade.blog'),
																			'ROLE_USER' => $this->get('translator')->trans('admin.grade.user'))))
						->getForm();
		return $this->render('UAUserBundle:Admin:grade.select.html.twig', array(	'id' => $id,
																					'user' => $user,
																					'form' => $form->createView()));
	}

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function banAction($id) {
		$em = $this->getDoctrine()->getManager();
		$user = $em->find('UAUserBundle:User', $id);
		if (!$user) {
			throw $this->createNotFoundException('Aucun utilisateur n\'a été trouvé !');
		}
		$request = $this->getRequest();
		if($request->getMethod() == 'POST') {
			$em->remove($user);
			$em->flush();

			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('admin.ban.success'));

			return $this->redirect($this->generateUrl('UA_user_gestion'));
		}

		return $this->render('UAUserBundle:Admin:ban.html.twig', array('id' => $id,
																		'user' => $user));
	}

	public function jsonAction() {
		$em = $this->getDoctrine()->getManager();
		$term = $this->getRequest()->query->get('term');
		$users = $em->getRepository('UAUserBundle:User')->getUsernamesByRegex($term);

		$userTable = array();
		foreach ($users as $user) {
			$userTable[] = array(	'value' => $user->getUsername(), 
									'label' => $user->getUsername(),
									'desc' => $user->getRoles());
		}

		return new JsonResponse($userTable);
	}
}