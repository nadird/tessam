<?php
namespace App\Controller\Dashboard\hr;



use App\Entity\EmplJobs;
use App\Entity\Employee;
use App\Form\Dashboard\config\JobType;
use App\Form\Dashboard\security\EmployeeAdminEditType;
use App\Form\Dashboard\security\EmployeeCreateType;
use App\Repository\EmplJobsRepository;
use App\Repository\EmployeeRepository;
use App\Service\ImageUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/hr', name: 'app_dashboard_hr_')]
class HrBaseController extends AbstractController
{
    public function __construct(private EmployeeRepository $employeeRepository,
								private EmplJobsRepository $emplJobsRepository,
    private RequestStack $requestStack,
    private ImageUploadService $imageUploadService,
    private UserPasswordHasherInterface $userPasswordHasher,
    private EntityManagerInterface $entityManager,
    
    ){
    }
    #[Route('/', name: 'index')]
public function index(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('dashboard/hr/index.html.twig', [
            'controller_name' => 'HrBaseController',
        ]);
    }
	#[Route('/employees', name: 'employees')]
	public function employees(): \Symfony\Component\HttpFoundation\Response
	{
		$employees = $this->employeeRepository->findAll();
		
		return $this->render('dashboard/hr/employees.html.twig', [
			'controller_name' => 'Employees Index',
			'employees' => $employees
		]);
	}
	#[Route('/employees/new', name: 'new_employee')]
	public function newEmployee(): \Symfony\Component\HttpFoundation\Response
	{
		$this->denyAccessUnlessGranted('ROLE_CAN_ADD_EMPLOYEE');
		$employee = new Employee();
		$newEmployeeForm = $this->createForm(EmployeeCreateType::class, $employee);
		$newEmployeeForm->handleRequest($this->requestStack->getCurrentRequest());
		if ($newEmployeeForm->isSubmitted() && $newEmployeeForm->isValid()) {
			$email = $newEmployeeForm->get('email')->getData();
			if($this->employeeRepository->findOneByEmail($email)){
				$this->addFlash('error', 'Email already exists');
				return $this->redirectToRoute('app_dashboard_hr_new_employee');
			}
			$picutre = $newEmployeeForm->get('picture')->getData();
			if($picutre){
				$newpicture= $this->imageUploadService->uploadImage($picutre, $this->getParameter('employee_directory'));
				$employee->setPicture($newpicture);
			}
			
			$plainpassword = $newEmployeeForm->get('password')->getData();
			$plainpassword = $this->userPasswordHasher->hashPassword($employee, $plainpassword);
			$employee->setPassword($plainpassword);
			$roles_to_add= $employee->getJob()->getRoles();
			$r = [];
			foreach ($roles_to_add as $ra){
				$r[] = $ra->getRole();
			}
			$employee->setRoles($r);
			$employee->setIsActive(true);
			$this->entityManager->persist($employee);
			$this->entityManager->flush();
			
			$this->addFlash('success', 'Employee Created');
			return $this->redirectToRoute('app_dashboard_hr_employees');
		}
		
		return $this->render('dashboard/hr/new_employee_form.html.twig', [
			'controller_name' => 'New Employee',
			'newEmployeeForm' => $newEmployeeForm->createView()
		]);
	}
	#[Route('/employees/edit/{id}', name: 'edit_employee')]
	public function editEmployee($id): \Symfony\Component\HttpFoundation\Response
	{
		$this->denyAccessUnlessGranted('ROLE_CAN_EDIT_EMPLOYEE');
		$employee = $this->employeeRepository->findOneById($id);
		$employeeConfig = clone $employee;
		$newEmployeeForm = $this->createForm(EmployeeAdminEditType::class, $employee);
		$newEmployeeForm->handleRequest($this->requestStack->getCurrentRequest());
		if ($newEmployeeForm->isSubmitted() && $newEmployeeForm->isValid()) {
			$roles_to_add= $employee->getJob()->getRoles();
			$r = [];
			foreach ($roles_to_add as $ra){
				$r[] = $ra->getRole();
			}
			$employee->setRoles($r);
			if($employeeConfig->getJob() != $employee->getJob()){
				//$roles_to_add= $employee->getJob()->getRoles();
				$roles_to_remove = $employeeConfig->getJob()->getRoles();
				$r = [];
				foreach ($roles_to_remove as $ra){
					$r[] = $ra->getRole();
				}
				
				// Some logic to remove the old job and add the new one
				/**
				 * TODO Review the logic here
				 */
			}
			
			$picutre = $newEmployeeForm->get('picture')->getData();
			if($picutre){
				$newpicture= $this->imageUploadService->uploadImage($picutre, $this->getParameter('employee_directory'));
				$employee->setPicture($newpicture);
			}
			
			$this->entityManager->persist($employee);
			$this->entityManager->flush();
			//dd($employee->getRoles());
			$this->addFlash('success', 'Employee Updated');
			return $this->redirectToRoute('app_dashboard_hr_employees');
		}
		
		return $this->render('dashboard/hr/update_employee_form.html.twig', [
			'controller_name' => 'Edit Employee',
			'employee' => $employee,
			'newEmployeeForm' => $newEmployeeForm->createView()
		]);
	}
	
	
	#[Route('/jobs/index', name: 'jobs_index')]
	public function jobs(): \Symfony\Component\HttpFoundation\Response
	{
		$jobs = $this->emplJobsRepository->findAll();
		
		return $this->render('dashboard/hr/jobs.html.twig', [
			'controller_name' => 'Jobs Index',
			'jobs' => $jobs
		]);
	}
	#[Route('/job/edit/{id}', name: 'job_edit')]
	public function job($id): \Symfony\Component\HttpFoundation\Response
	{
		$job = $this->emplJobsRepository->findOneById($id);
		$newJobForm = $this->createForm(JobType::class, $job);
		$newJobForm->handleRequest($this->requestStack->getCurrentRequest());
		if ($newJobForm->isSubmitted() && $newJobForm->isValid()) {
			$this->entityManager->persist($job);
			$this->entityManager->flush();
			$this->addFlash('success', 'Job Updated');
			return $this->redirectToRoute('app_dashboard_hr_jobs_index');
		}
		return $this->render('dashboard/hr/job_form.html.twig', [
			'controller_name' => 'Edit Job ',
			'form'=> $newJobForm->createView(),
			'jobs' => $job
		]);
	}
	#[Route('/job/new/', name: 'job_new')]
	public function jobnew(): \Symfony\Component\HttpFoundation\Response
	{
		$job = new EmplJobs();
		$newJobForm = $this->createForm(JobType::class, $job);
		$newJobForm->handleRequest($this->requestStack->getCurrentRequest());
		if ($newJobForm->isSubmitted() && $newJobForm->isValid()) {
			$this->entityManager->persist($job);
			$this->entityManager->flush();
			$this->addFlash('success', 'Job Updated');
			return $this->redirectToRoute('app_dashboard_hr_jobs_index');
		}
		return $this->render('dashboard/hr/job_form.html.twig', [
			'controller_name' => 'New Job',
			'form'=> $newJobForm->createView(),
		]);
	}
    
}