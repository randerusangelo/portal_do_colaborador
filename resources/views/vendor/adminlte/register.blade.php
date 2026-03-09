@extends('adminlte::master')

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', 'register-page')

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )
@php( $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
    @php( $dashboard_url = $dashboard_url ? route($dashboard_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
    @php( $dashboard_url = $dashboard_url ? url($dashboard_url) : '' )
@endif

@section('body')
    <div class="register-box">
        <div class="register-logo">
            <a href="{{ $dashboard_url }}">{!! config('adminlte.logo', '<b>USA</b>ngelo') !!}</a>
        </div>
        <div class="card">
            <div class="card-body register-card-body">

                <p class="login-box-msg">{{ __('adminlte::adminlte.register_message') }}</p>

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ $register_url }}" method="post">
                    {{ csrf_field() }}

                    <div class="input-group mb-2">
                        <input type="number" name="matricula" class="form-control {{ $errors->has('matricula') ? 'is-invalid' : '' }}" value="{{ old('matricula') }}"
                               placeholder="{{ __('adminlte::adminlte.matriculation') }}" autocomplete="off" autofocus min="1">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-tag"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-2">
                        <input type="text" name="nome" class="form-control {{ $errors->has('nome') ? 'is-invalid' : '' }}" value="{{ old('nome') }}"
                               placeholder="{{ __('adminlte::adminlte.nome') }}" autocomplete="off">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-2">
                        <input type="text" name="sobrenome" class="form-control {{ $errors->has('sobrenome') ? 'is-invalid' : '' }}" value="{{ old('sobrenome') }}"
                               placeholder="{{ __('adminlte::adminlte.sobrenome') }}" autocomplete="off">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-2">
                        <input type="text" name="cpf" class="form-control {{ $errors->has('cpf') ? 'is-invalid' : '' }}" value="{{ old('cpf') }}"
                               placeholder="{{ __('adminlte::adminlte.cpf') }}" maxlength="14" autocomplete="off">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-2 mr-0">
                        <input type="text" name="data_nascimento" class="form-control {{ $errors->has('data_nascimento') ? 'is-invalid' : '' }}" value="{{ old('data_nascimento') }}"
                               placeholder="{{ __('adminlte::adminlte.birth_date') }}"  maxlength="14" onfocus="(this.type='date')" onblur="this.type=(this.value!=''?'date':'text')" autocomplete="off">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-calendar"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-2">
                        <input type="text" name="nome_mae" class="form-control {{ $errors->has('nome_mae') ? 'is-invalid' : '' }}" value="{{ old('nome_mae') }}"
                               placeholder="{{ __('adminlte::adminlte.nameMom') }}" autocomplete="off">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-2">
                        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}"
                               placeholder="{{ __('adminlte::adminlte.email') }}" autocomplete="off">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-2">
                        <input type="password" name="senha" class="form-control {{ $errors->has('senha') ? 'is-invalid' : '' }}"
                               placeholder="{{ __('adminlte::adminlte.password') }}" autocomplete="off">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-2">
                        <input type="password" name="senha_confirmation" class="form-control {{ $errors->has('senha_confirmation') ? 'is-invalid' : '' }}"
                               placeholder="{{ __('adminlte::adminlte.retype_password') }}" autocomplete="off">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-2">
                        {!! app('captcha')->display() !!}
                    </div>

                    <div class="input-group mb-2">
                        <div class="form-check text-justify">
                            <input type="checkbox" class="form-check-input" name="check_termo" value="1">
                            <label class="form-check-label" for="check_termo">Li e concordo com o</label>
                            <a class="btn-link" data-toggle="modal" data-target=".bd-example-modal-lg"> Termo de Adesão e Responsabilidade </a>
                            <label class="form-check-label" for="check_termo">para acesso no ambiente virtual.</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                        {{ __('adminlte::adminlte.register') }}
                    </button>
                </form>
                <p class="mt-2 mb-1 text-center">
                    <a href="{{ $login_url }}">
                        {{ __('adminlte::adminlte.i_already_have_a_membership') }}
                    </a>
                </p>

                {{-- Modal --}}
                <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">

                            <div class="modal-header" style="justify-content: center;">
                                <h5 class="modal-title font-weight-bold">{{ $vTitulo }}</h5>
                            </div>
                            
                            <div class="modal-body text-justify">
                                {!! $vTexto !!}
                                {!! $vCidadeData !!}
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>

                        </div>
                    </div>
                </div>

            </div><!-- /.card-body -->
        </div><!-- /.card -->
    </div><!-- /.register-box -->
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @stack('js')
    @yield('js')
@stop
