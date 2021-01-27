<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/users", name="admin_users_")
 */
class UserController extends AbstractController
{
	/**
	 * @Route("/", name="index")
	 */
	public function index(UserRepository $userRepository)
	{
		$users = $userRepository->findAll();

		return $this->render('admin/user/index.html.twig', compact('users'));
	}

	/**
	 * @Route("/create", name="create")
	 */
	public function create(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
	{
		$form = $this->createForm(UserType::class);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {

			$password = $passwordEncoder->encodePassword(new User(), $form['password']->getData());

			$user = $form->getData();
			$user->setPassword($password);

			$em->persist($user);
			$em->flush();

			$this->addFlash('success', 'Usuário criado com sucesso!');

			return $this->redirectToRoute('admin_users_index');
		}

		return $this->render('admin/user/create.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/edit/{user}", name="edit")
	 */
	public function edit(User $user, Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
	{
		$form = $this->createForm(UserType::class, $user);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {

			$password = $form['password']->getData();

			if($password) {
				$password = $passwordEncoder->encodePassword($user, $password);
				$user->setPassword($password);
			}

			$em->flush();

			$this->addFlash('success', 'Usuário atualizado com sucesso!');

			return $this->redirectToRoute('admin_users_edit', ['user' => $user->getId()]);
		}

		return $this->render('admin/user/edit.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/remove/{user}", name="remove")
	 */
	public function remove(User $user)
	{
		try{
			$manager = $this->getDoctrine()->getManager();
			$manager->remove($user);
			$manager->flush();

			$this->addFlash('success', 'Usuário removido com sucesso!');

			return $this->redirectToRoute('admin_users_index');

		} catch (\Exception $e) {
			die($e->getMessage());
		}
	}
}
