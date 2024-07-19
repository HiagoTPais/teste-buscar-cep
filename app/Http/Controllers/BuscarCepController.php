<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BuscarCepController extends Controller
{
    public function buscarCep(Request $request)
    {
        $listaCep = $request->cep;
        $arrayCep = explode(",", $listaCep);
        $responseCep = [];

        foreach ($arrayCep as $key => $value) {
            if ($value) {
                $cep = preg_replace("/[^0-9]/", "", $value);

                $jsonCep = $this->getCep($cep);

                if (!$jsonCep) {
                    $jsonCep = [
                        "erro" => "Cep {$cep}, não foi encontrado."
                    ];
                }

                array_push($responseCep, $jsonCep);
            }
        }

        return $responseCep;
    }

    private function getCep($cep)
    {
        $url = "https://viacep.com.br/ws/{$cep}/json/";

        try {
            $response = Http::get($url);
        } catch (\Exception $erro) {
            return response([
                'message' => $erro->getMessage(),
                'code' => $erro->getCode()
            ], 422);
        }

        return $response->json();
    }
}
