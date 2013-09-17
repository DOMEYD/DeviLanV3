<?
namespace UA\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller {

	public function indexAction() {
		return $this->render('UAUserBundle:Admin:index.html.twig');
	}
}