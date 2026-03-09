<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Requests\AptidaoFormRequest;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class AptidaoController extends Controller
{
    public function home()
    {
        $dados = null;
        return view('aptidao-funcionario', [
            'dados' => $dados
        ]);
    }

    public function getAptidao(AptidaoFormRequest $request)
    {

        $validator = Validator::make($request->all(), [
            'dia_nasc'  => 'required',
            'matricula' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $logController = new LogAptidaoController();

        $client = new Client([
            "auth" => [
                config('sap.auth.login'),
                config("sap.auth.password")
            ]
        ]);

        $response = $client->request("GET", config('sap.api.hr.aptidao'), [
            "headers" => [
                "Content-Type" => "application/x-www-form-urlencoded",
                "Accept"       => "*/*",
            ],
            "query" => [
                "tipo"      => 1,
                "matricula" => $request->matricula,
                "dia"       => $request->dia_nasc
            ]
        ]);

        $dados = json_decode($response->getBody()->getContents());

        if (!isset($dados->funcionario)) {
            return view('aptidao-funcionario', [
                'matricula' => $request->matricula,
                'dia_nasc'  => $request->dia_nasc,
                'api_error' => $dados,
            ]);
        }

        // Buscar imagem
        $imagePath = 'images/' . $request->matricula . '.JPG';
        $girarImagem = 0;

        if (Storage::disk('private')->exists($imagePath)) {
            $imageContent = Storage::disk('private')->get($imagePath);
            $base64Image = base64_encode($imageContent);

            $imagePath = Storage::path('private/' . $imagePath);
            $image     = Image::make($imagePath)->orientate();
            if ($image->width() > $image->height()) {
                $girarImagem = 1;
            }
        } else {
            $base64Image = base64_encode(file_get_contents(public_path('storage/images/profile.jpg')));
        }

        foreach ($dados->t_aso_func_det as $dado) {
            if (strlen($dado->data) > 0) {
                $dado->data = Carbon::parse($dado->data)->format("d/m/Y");   
            }

            $dado->situacao_cor = "";
            $dado->situacao_texto = "";

            if ($dado->situacao == "A") {
                $dado->situacao_cor = "success";
                $dado->situacao_texto = "VÁLIDO";
            }

            if ($dado->situacao == "I") {
                $dado->situacao_cor = "danger";
                $dado->situacao_texto = "EXPIRADO";
            }

            if ($dado->situacao == "P") {
                $dado->situacao_cor = "secondary";
                $dado->situacao_texto = "PENDENTE";
            }

            if ($dado->situacao == "W") {
                $dado->situacao_cor = "warning";
                $dado->situacao_texto = "VENCENDO";
            }

        }

        $observacoes = null;

        if (strlen($dados->observacao) > 0) {
            $observacoes = explode(";", $dados->observacao);
        }

        $logController->store($request);

        return view('aptidao-funcionario', [
            'matricula' => $dados->funcionario,
            'nome' => $dados->nome,
            'dados' => $dados->t_aso_func_det,
            'ocupacao' => $dados->funcao,
            'observacoes' => $observacoes,
            'girarImagem' => $girarImagem,
            'file' => 'data:image/jpeg;base64,' . $base64Image,
        ]);
    }
}
