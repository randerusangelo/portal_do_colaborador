@extends('adminlte::page')

@section('title')

@section('content_header')
    <h1 class="m-0 text-dark">Vídeos Explicativos</h1>
@stop

@section('content')

    {{-- Assédio Moral --}}
    <div class="row">
        <div class="col-md">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7">
                            <h3>Assédio Moral</h3>                            
                            <h6>Você poderá compreender um pouco mais sobre o tema assédio moral. Os impactos negativos que este tipo de irregularidade traz para a empresa 
                                e para as pessoas que fazem parte dela.</h6>
                            <br>
                            <h6>Publicado em 06/12/2023</h6>
                        </div>
                        <div class="col-md-5">
                            {{-- <iframe src="https://drive.google.com/file/d/1tS27Bv0gyo_es1Fhcas9cuaogNUlKWXW/preview" width="100%" height="auto" allow="autoplay"></iframe> --}}
                            <video class="video-usa" width="100%" height="auto" controls="controls" name="Video Name" style="border-radius: 15px; border: 1px solid #999;">
                                <source src="{{ asset('storage/videos/Assédio Moral.mp4') }}">
                            </video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Assédio Sexual --}}
    <div class="row">
        <div class="col-md">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7">
                            <h3>Assédio Sexual</h3>
                            <h6>No ambiente de trabalho, o <b>assédio sexual</b> é identificado como um constrangimento de viés sexual e, <b>sempre</b>, deve ser utilizado o canal de 
                                denúncias imediatamente.<br>
                                A empresa e as pessoas devem manter sempre um clima de <b>respeito</b> e <b>harmonia</b>.
                            </h6>
                            <br>
                            <h6>Publicado em 06/12/2023</h6>
                        </div>
                        <div class="col-md-5">
                            {{-- <iframe src="https://drive.google.com/file/d/1qLff8FeOfJh_p7jtNV0Mlh5F6QMYs4SR/preview" width="640" height="480" allow="autoplay"></iframe> --}}
                            <video class="video-usa" width="100%" height="auto" controls="controls" name="Video Name" style="border-radius: 15px; border: 1px solid #999;">
                                <source src="{{ asset('storage/videos/Assédio Sexual.mp4') }}">
                            </video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Discriminação --}}
    <div class="row">
        <div class="col-md">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7">
                            <h3>Discriminação</h3>
                            <h6>Formas de discriminação mais comuns e como elas podem ocorrer no dia a dia de trabalho. Assim como os <b>efeitos negativos</b> que trazem a esse espaço.</h6>
                            <br>
                            <h6>Publicado em 06/12/2023</h6>
                        </div>
                        <div class="col-md-5">
                            {{-- <iframe src="https://drive.google.com/file/d/1RHBdYU0HgEktDVmqpKQoE3fRyCe34WmJ/preview" width="640" height="480" allow="autoplay"></iframe> --}}
                            <video class="video-usa" width="100%" height="auto" controls="controls" name="Video Name" style="border-radius: 15px; border: 1px solid #999;">
                                <source src="{{ asset('storage/videos/Discriminação.mp4') }}">
                            </video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Diversidade --}}
    <div class="row">
        <div class="col-md">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7">
                            <h3>Diversidade</h3>
                            <h6>Neste vídeo, você poderá aprender mais sobre o tema. O ato de acolher as diferenças trará um efeito positivo para todos.</h6>
                            <br>
                            <h6>Publicado em 06/12/2023</h6>
                        </div>
                        <div class="col-md-5">
                            {{-- <iframe src="https://drive.google.com/file/d/1-wTo6kVx-VO5TRLDWsFci60xXW3JSnTS/preview" width="640" height="480" allow="autoplay"></iframe> --}}
                            <video class="video-usa" width="100%" height="auto" controls="controls" name="Video Name" style="border-radius: 15px; border: 1px solid #999;">
                                <source src="{{ asset('storage/videos/Diversidade.mp4') }}">
                            </video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop