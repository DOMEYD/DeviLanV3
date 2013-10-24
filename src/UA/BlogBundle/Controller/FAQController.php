<?php 
namespace UA\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;

use UA\BlogBundle\Entity\FAQ;
use UA\BlogBundle\Form\FAQType;

class FAQController extends Controller
{	
	/*
	 * VIEW
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$faq = $em->getRepository('UABlogBundle:FAQ')->findAll();
		return $this->render('UABlogBundle:FAQ:index.html.twig', array('faq' => $faq));
	}

	/*
	 * ADMIN
	 */

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function addAction($id) {
		$em = $this->getDoctrine()->getManager();
		if($id) {
			$newQuestion = $em->find('UABlogBundle:FAQ', $id);
		} else {
			$newQuestion = new FAQ();
		}
		$form = $this->createForm(new FAQType, $newQuestion);

		//Handle post request
		$request = $this->getRequest();
		if($request->getMethod() == 'POST') {
			$form->bind($request);
			if($form->isValid()) {
				$em->persist($newQuestion);
				$em->flush();

				//Empty the form
				$form = $this->createForm(new FAQType, null);
			}
		}

		//Recovery entirety faq
		$faq = $em->getRepository('UABlogBundle:FAQ')->findBy(array(), array('id' => 'DESC'));

		return $this->render('UABlogBundle:Admin:faq.add.html.twig', array(	'form' => $form->createView(),
																			'faq' => $faq));
	}

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function deleteAction($id) {
		$em = $this->getDoctrine()->getManager();
		$faqDelete = $em->find('UABlogBundle:FAQ', $id);

		//Generate CRCF
		$form = $this->createFormBuilder()->getForm();

		//handle request
		$request = $this->getRequest();
		if($request->getMethod() == "POST") {
			$form->bind($request);
			if($form->isValid()) {
				//remove item
				$em->remove($faqDelete);
				$em->flush();

				//redirect to add faq
				return $this->redirect($this->generateUrl('UAFAQ_add'));
			}
		}

		return $this->render('UABlogBundle:Admin:faq.delete.html.twig', array(	'form' => $form->createView(),
																				'faq' => $faqDelete));
	}
}