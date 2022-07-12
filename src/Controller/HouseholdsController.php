<?php

namespace App\Controller;

use App\Households\Generator\GroupByOwnersAlgorithm;
use App\Households\Generator\HouseholdsGenerator;
use App\Households\Parser\ApiContractParser;
use App\Model\ModelFactory;
use App\Model\ModelFactoryValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HouseholdsController extends AbstractController
{
    const RESPONSE_TYPE_VALIDATION_ERROR = 'validation_error';
    const RESPONSE_TYPE_GENERAL_ERROR = 'general_error';

    const RESPONSE_STATUS_SUCCESS = 200;
    const RESPONSE_STATUS_VALIDATION_ERROR = 400;
    const RESPONSE_STATUS_GENERAL_ERROR = 500;

    // TECHNICAL
    //TODO Customize default 404 page
    //TODO More response status type an codes like 401 Unauthorized or 204 for empty response
    //TODO Documentation
    //TODO Analyze and Provide stress test

    // IDEAS
    //TODO Does not stop create model if something have been wrong but continue read and send response with all data that have been successfully read
    //     and of course send information about unsuccessfully read parts of data.

    #[Route('/households', name: 'households', methods: ['POST'])]
    function index(Request $request): Response
    {
        try{

            $requestData = json_decode($request->getContent(), true);

            // TODO add some pre-validation and move the code below to some service
            $factory = new ModelFactory();
            $bank = $factory->createFromArray($requestData);

            $algorithm = new GroupByOwnersAlgorithm();
            $householdsGenerator = new HouseholdsGenerator($algorithm);

            $households = $householdsGenerator->process($bank);

            // TODO implement different households parser to provide more information like merge holdings of all accounts in households, in feature version of api
            $parser = new ApiContractParser();

            $data = $parser->parse($households);

            $status = self::RESPONSE_STATUS_SUCCESS;

        }catch (ModelFactoryValidationException $e){
            $status = self::RESPONSE_STATUS_VALIDATION_ERROR;
            $data = [
                'status' => $status,
                'type' => self::RESPONSE_TYPE_VALIDATION_ERROR,
                'message' => $e->getMessage()
            ];
        }catch(\Throwable $e){
            // TODO log error
            $status = self::RESPONSE_STATUS_GENERAL_ERROR;
            $data = [
                'status' => $status,
                'type' => self::RESPONSE_TYPE_GENERAL_ERROR,
            ];
        }

        return $this->json($data, $status, [
            "Content-Type" => "application/v1+json"
        ]);
    }
}