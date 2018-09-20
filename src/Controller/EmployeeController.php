<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/employees")
 */
class EmployeeController extends ParentApiController {

    /**
     * @Route("/")
     * @Method("GET")
     */
    public function getEmployeesAll(EmployeeRepository $employeeRepository) {
        $employees = $employeeRepository->transformAll();
        return $this->respond($employees);
    }

    /**
     * @Route("/{id}")
     * @Method("GET")
     */
    public function getEmployeeById($id, EmployeeRepository $employeeRepository) {
        $employee = $employeeRepository->find($id);
        $employee = $employeeRepository->transform($employee);
        return $this->respond($employee);
    }

    /**
    * @Route("/create")
    * @Method("POST")
    */
    public function create(Request $request, EmployeeRepository $employeeRepository, EntityManagerInterface $em)
    {
        $request = $this->transformJsonBody($request);
        // persist the new employee
        $employee = new Employee;
        $employee->setFirstName($request->get('firstName'));
        $employee->setLastName($request->get('lastName'));
        $employee->setPhone($request->get('phone'));
        $em->persist($employee);
        $em->flush();

        return $this->respondCreated($employeeRepository->transform($employee));
    }

    /**
     * @Route("/update/{id}")
     * @Method("PUT")
     */
    public function updateEmployee($id, Request $request, EmployeeRepository $employeeRepository, EntityManagerInterface $em) {
        $request = $this->transformJsonBody($request);

        $employee = $employeeRepository->find($id);
        $employee->setFirstName($request->get('firstName'));
        $employee->setLastName($request->get('lastName'));
        $employee->setPhone($request->get('phone'));

        $em->persist($employee);
        $em->flush();

        return $this->respondUpdated($employeeRepository->transform($employee));
    }

    /**
     * @Route("/remove/{id}")
     * @Method("DELETE")
     */
    public function deleteEmployee($id, Request $request, EmployeeRepository $employeeRepository, EntityManagerInterface $em) {
        $request = $this->transformJsonBody($request);

        $employee = $employeeRepository->find($id);
        if(!$employee) {
            throw $this->createNotFoundException('No employee found for id '.$id);
        }
        $em->remove($employee);
        $em->flush();

        return $this->respond($employeeRepository->transform($employee));
    }
}
?>