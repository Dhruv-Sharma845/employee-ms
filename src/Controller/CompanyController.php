<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use App\Repository\EmployeeRepository;

/**
 * @Route("/companies")
 */
class CompanyController extends ParentApiController {
    
    /**
     * @Route("/")
     * @Method("GET")
     */
    public function getCompaniesAll(CompanyRepository $companyRepository) {
        $companies = $companyRepository->transformAll();
        if(!$companies) {
            throw $this->createNotFoundException('The employee does not exist for the id'.$id); 
        }

        //return $this->respond($companies);
        return $this->render("companies_list.html.twig",[
            "companies" => $companies
        ]);
    }

    /**
     * @Route("/ById/{id}")
     * @Method("GET")
     */
    public function getCompanyById($id, CompanyRepository $companyRepository) {
        $company = $companyRepository->find($id);
        $company = $companyRepository->transform($company);
        return $this->respond($company);
    }

    /**
    * @Route("/create")
    * @Method("POST")
    */
    public function create(Request $request, CompanyRepository $companyRepository,EntityManagerInterface $em)
    {
        $request = $this->transformJsonBody($request);
        // persist the new company
        $company = new Company;
        $company->setName($request->get('name'));
        $company->setPhone($request->get('phone'));
        $company->setAddress($request->get('address'));
        $em->persist($company);
        $em->flush();

        return $this->respondCreated($companyRepository->transform($company));
    }

    /**
     * @Route("/remove/{id}", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteCompany($id, Request $request, CompanyRepository $companyRepository, EntityManagerInterface $em) {
        $request = $this->transformJsonBody($request);

        $company = $companyRepository->find($id);
        if(!$company) {
            throw $this->createNotFoundException('No company found for id '.$id);
        }
        $em->remove($company);
        $em->flush();

        return $this->respond($companyRepository->transform($company));
    }

    /**
    * @Route("/{id}/addEmployee/{empId}")
    * @Method("POST")
    */
    public function addEmployee($id, $empId, Request $request, CompanyRepository $companyRepository,  EmployeeRepository $employeeRepository, EntityManagerInterface $em)
    {
        $request = $this->transformJsonBody($request);
        $company = $companyRepository->find($id);
        $employee = $employeeRepository->find($empId);
        $company->addEmployee($employee);
        $em->persist($company);
        $em->flush();

        return $this->respondCreated($companyRepository->transform($company));
    }
}
?>