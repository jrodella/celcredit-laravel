<?php

namespace Celcredit\Clients;

use Celcredit\Types\Person;
use Celcredit\Types\Business;
use Celcredit\Types\Document;
use Celcredit\Types\Relation;
use Celcredit\Types\Signature;
use GuzzleHttp\RequestOptions;
use Celcredit\Types\Simulation;
use Celcredit\Types\Application;

use Celcredit\Rules\Pix as PixRule;
use Celcredit\Common\CelcreditBaseApi;
use Celcredit\Rules\Phone as PhoneRule;
use Illuminate\Support\Facades\Storage;
use Celcredit\Rules\Person as PersonRule;
use Illuminate\Support\Facades\Validator;
Use Celcredit\Rules\Relation as RelationRule;
Use Celcredit\Rules\Signature as SignatureRule;
use Celcredit\Rules\Address as AddressRule;
use Celcredit\Rules\Business as BusinessRule;

use Celcredit\Rules\Document as DocumentRule;
use Celcredit\Rules\Simulation as SimulationRule;
use Celcredit\Rules\Application as ApplicationRule;


class BankingOriginatorClient extends CelcreditBaseApi
{
    // Rotas definidas como constantes
    public const CREATE_PERSON = '/banking/originator/persons';
    public const ADD_DOCUMENT = '/banking/originator/persons/%s/documents';
    public const CREATE_BUSINESS = '/banking/originator/business';
    public const LINK_RELATION = '/banking/originator/business/%s/relations';
    public const SIMULATE = '/banking/originator/applications/preview';
    public const SIMULATE_TOTAL_AMOUNT = '/banking/originator/applications/preview-total-amount';
    public const CREATE_APPLICATION = '/banking/originator/applications';
    public const SIGN_APPLICATION = '/banking/originator/applications/%s/signatures';
    public const VIEW_APPLICATION = '/banking/originator/applications/%s/agreement';

    // 1. Cadastro de Pessoa
    public function createPerson(Person $person): array
    {
        $data = $person->toArray();
        $this->validateRequest(
            $data,
            PersonRule::rules(),
            [
                'phone' => PhoneRule::rules(),
                'address' => AddressRule::rules(),
                'pix' => PixRule::rules()
            ]
        );

        return $this->post(self::CREATE_PERSON, $data);
    }

    // 2. Cadastro de Documento (Multipart)
    public function addDocument(string $personId, Document $document): array
    {
        $this->validateRequest($document->toArray(), DocumentRule::rules());

        return $this->post(
            sprintf(self::ADD_DOCUMENT, $personId),
            $this->createMultipartPayload($document)
        );
    }

    // 3. Cadastro de Empregador
    public function createBusiness(Business $business): array
    {
        $data = $business->toArray();
        $this->validateRequest(
            $data,
            BusinessRule::rules(),
            [
                'phone' => PhoneRule::rules(),
                'address' => AddressRule::rules(),
                'pix' => PixRule::rules()
            ]
        );

        return $this->post(self::CREATE_BUSINESS, $data);
    }

    // 4. Vincular Pessoa x Empregador
    public function linkRelation(string $businessId, Relation $relation): array
    {
        $this->validateRequest($relation->toArray(), RelationRule::rules());

        return $this->post(
            sprintf(self::LINK_RELATION, $businessId),
            $relation->toArray()
        );
    }

    // 5. Simular Solicitação
    public function simulate(Simulation $simulation): array
    {
        $this->validateRequest($simulation->toArray(), SimulationRule::rules());

        return $this->post(self::SIMULATE, $simulation->toArray());
    }

    // 5. Simular Solicitação
    public function simulateTotalAmount(Simulation $simulation): array
    {
        $this->validateRequest($simulation->toArray(), SimulationRule::rules());

        return $this->post(self::SIMULATE_TOTAL_AMOUNT, $simulation->toArray());
    }

    // 6. Criar Solicitação
    public function createApplication(Application $application): array
    {
        $this->validateRequest($application->toArray(), ApplicationRule::rules());

        return $this->post(self::CREATE_APPLICATION, $application->toArray());
    }

    // 7. Assinatura de Solicitação
    public function signApplication(string $applicationId, Signature $signature): array
    {
        $this->validateRequest($signature->toArray(), SignatureRule::rules());

        return $this->post(
            sprintf(self::SIGN_APPLICATION, $applicationId),
            $signature->toArray()
        );
    }

    // 8. Download CCB
    public function viewApplication(string $applicationId, $filename): array
    {
        $storagePath = storage_path("app/download/{$filename}");
        Storage::makeDirectory(dirname($storagePath));

        return $this->get(
            sprintf(self::VIEW_APPLICATION, $applicationId),
            [
                RequestOptions::SINK => $storagePath,
            ]
        );
    }

    private function validateRequest(
        array $data,
        array $mainRules,
        array $nestedRules = []
    ): void {
        foreach ($nestedRules as $key => $rules) {
            $mainRules[$key] = ['nullable', 'array'];
            foreach ($rules as $nestedKey => $nestedRule) {
                $mainRules["$key.$nestedKey"] = $nestedRule;
            }
        }

        $validator = Validator::make($data, $mainRules);

        if ($validator->fails()) {
            throw new \InvalidArgumentException(
                json_encode($validator->errors()->toArray())
            );
        }
    }

    private function createMultipartPayload(Document $document): array
    {
        $file = $document->file;

        $contents = $file instanceof \Illuminate\Http\UploadedFile
            ? $file->get()
            : fopen($file->getRealPath(), 'r');

        $filename = $file instanceof \Illuminate\Http\UploadedFile
            ? $file->getClientOriginalName()
            : $file->getFilename();

        return [
            'multipart' => [
                ['name' => 'type', 'contents' => $document->type],
                ['name' => 'file', 'contents' => $contents, 'filename' => $filename]
            ]
        ];
    }
}
