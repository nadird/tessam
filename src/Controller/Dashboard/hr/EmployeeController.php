<?php
	namespace App\Controller\Dashboard\hr;
	
	
	
	use App\Entity\Employee;
	use App\Form\Dashboard\security\EmployeeChangePasswordType;
	use App\Form\Dashboard\security\EmployeeUpdateType;
	use App\Form\EmployeeType;
	use App\Repository\EmployeeRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\String\Slugger\SluggerInterface;
	
	#[Route('/dashboard/hr/employee', name: 'app_dashboard_hr_employee_')]
	class EmployeeController extends AbstractController
	{
		public function __construct(
			private EntityManagerInterface $entityManager,
			private EmployeeRepository $employeeRepository,
			private RequestStack $requestStack,
			private SluggerInterface $slugger,
			private UserPasswordHasherInterface $userPasswordHasher
		){
		}
		#[Route('/', name: 'index')]
		public function index(): \Symfony\Component\HttpFoundation\Response
		{
			return $this->render('dashboard/hr/employee/index.html.twig', [
				'controller_name' => 'EmployeeController',
			]);
		}
		#[Route('/profile/{id}', name: 'employee_profile')]
		public function profile($id): \Symfony\Component\HttpFoundation\Response
		{
			$employee = $this->employeeRepository->findOneById($id);
			$employeeForm = $this->createForm(EmployeeUpdateType::class, $employee);
			$employeeForm->handleRequest($this->requestStack->getCurrentRequest());
			if ($employeeForm->isSubmitted() && $employeeForm->isValid()) {
				$image = $employeeForm->get('picture')->getData();
				//dd($image);
				if ($image) {
					$originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
					$safeFilename = $this->slugger->slug($originalFilename);
					$newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
					$image->move(
						$this->getParameter('employee_directory'),
						$newFilename
					);
					$employee->setPicture($newFilename);
				}
				$this->entityManager->persist($employee);
				$this->entityManager->flush();
				$this->addFlash('success', 'Employee Updated');
				return $this->redirectToRoute('app_dashboard_hr_employee_index');
			}
			
			return $this->render('dashboard/hr/employee/profile.html.twig', [
				'controller_name' => 'EmployeeController',
				'employee' => $employee,
				'employeeForm' => $employeeForm->createView(),
			]);
		}
		
		#[Route('/changePassword/{id}', name: 'change_password')]
		public function ChangePassword($id):Response
		{
			$employee = $this->employeeRepository->findOneById($id);
			if(!$employee instanceof Employee || $employee->getIsActive() === false) {
				throw new \LogicException('You are not allowed to connect here');
			}
			$currentUser = $this->getUser();
			if($this->getUser()->getId() !== $employee->getId() ) {
				throw new \LogicException('You are not allowed to connect here');
			}
			$employeeForm = $this->createForm(EmployeeChangePasswordType::class);
			$employeeForm->handleRequest($this->requestStack->getCurrentRequest());
			if ($employeeForm->isSubmitted() && $employeeForm->isValid()) {
				if(!$this->userPasswordHasher->isPasswordValid($employee, $employeeForm->get('CurrentPassword')->getData())) {
					$this->addFlash('danger', 'Wrong Password');
					return $this->redirectToRoute('app_dashboard_hr_employee_change_password', ['id' => $employee->getId()]);
				}else {
					$employee->setPassword($this->userPasswordHasher->hashPassword($employee, $employeeForm->get('password')->getData()));
					$this->entityManager->persist($employee);
					$this->entityManager->flush();
					$this->addFlash('success', 'Employee Updated');
					return $this->redirectToRoute('app_dashboard_hr_employee_employee_profile', ['id' => $employee->getId()]);
				}
				
			}
			
			return $this->render('dashboard/hr/employee/changePassword.html.twig', [
				'controller_name' => 'EmployeeController',
				'employee' => $employee,
				'passwordForm' => $employeeForm->createView(),
			]);
		}
	
		
		
	}