@extends('layouts.vertical')

@section('title') {{ __($module_action) }} {{ $module_title }} @endsection

@section('breadcrumb')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route='{{route("backend.$module_name.index")}}' icon='{{ $module_icon }}'>
        {{ $module_title }}
    </x-backend-breadcrumb-item>

    <x-backend-breadcrumb-item type="active">{{ __($module_action) }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-8">
                <h4 class="card-title mb-0">
                    <i class="{{$module_icon}}"></i> {{ __('labels.backend.users.edit.title') }}
                    <small class="text-muted">{{ __('labels.backend.users.edit.action') }} </small>
                </h4>
                <div class="small text-muted">
                    {{ __('labels.backend.users.edit.sub-title') }}
                </div>
            </div>
            <!--/.col-->
            <div class="col-4">
                <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                    <x-buttons.return-back />
                </div>
            </div>
        </div>
        <hr>

        <div class="row mt-4">
            <div class="col">
                {{ html()->modelForm($user, 'PATCH', route('backend.users.update', $user->id))->class('form-horizontal')->open() }}

                <div class="form-group row">
                    {{ html()->label(__('labels.backend.users.fields.email'))->class('col-sm-2 form-control-label')->for('email') }}

                    <div class="col-sm-10">
                        {{ html()->email('email')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.users.fields.email'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row">
                    {{ html()->label(__('labels.backend.users.fields.password'))->class('col-5 col-sm-2 form-control-label')->for('password') }}

                    <div class="col-7 col-sm-10">
                        <a href="{{ route('backend.users.changePassword', $user->id) }}"
                            class="btn btn-outline-primary btn-sm"><i class="fas fa-key"></i> Change password</a>
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row">
                    {{ html()->label('Profile')->class('col-5 col-sm-2 form-control-label')->for('profile') }}

                    <div class="col-7 col-sm-10">
                        <a href="{{ route("backend.users.profileEdit", $user->id) }}"
                            class="btn btn-outline-primary btn-sm"><i class="fas fa-user"></i> Update Profile</a>
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row">
                    {{ html()->label(__('labels.backend.users.fields.confirmed'))->class('col-5 col-sm-2 form-control-label')->for('confirmed') }}

                    <div class="col-7 col-sm-10">
                        @if ($user->email_verified_at == null)
                        <a href="{{route('backend.users.emailConfirmationResend', $user->id)}}"
                            class="btn btn-outline-primary btn-sm " data-toggle="tooltip"
                            title="Send Confirmation Email"><i class="fas fa-envelope"></i> Send Confirmation Email</a>
                        @else
                        {!! $user->confirmed_label !!}
                        @endif
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row">
                    {{ html()->label(__('Company'))->class('col-5 col-sm-2 form-control-label')->for('company ') }}

                    <div class="col-7 col-sm-10">
                        
                        @foreach($companies as $item)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="company_code" id="company"
                                    value="{{ $item->param_key }}" {{ $item->param_key == $user->company_code ? 'checked' : '' }} required>
                                <label class="form-check-label" for="company">{{ $item->param_text }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!--form-group-->


                <div class="form-group row">
                    {{ html()->label('Abilities')->class('col-sm-2 form-control-label') }}
                    <div class="col">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card bg-secondary">
                                    <div class="card-header">
                                        @lang('Roles')
                                    </div>
                                    <div class="card-body">
                                        @if ($roles->count())
                                        @foreach($roles as $role)
                                        <div class="card bg-secondary">
                                            <div class="card-header">
                                                <div class="custom-control custom-checkbox">
                                                    {{ html()->checkbox('roles[]', in_array($role->name, $userRoles), $role->name)->class('custom-control-input')->id('role-'.$role->id) }}
                                                    {{ html()->label($role->name)->class('custom-control-label')->for('role-'.$role->id) }}
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                @if ($role->id != 1)
                                                @if ($role->permissions->count())
                                                @foreach ($role->permissions as $permission)
                                                <i class="far fa-check-circle mr-1"></i>{{ $permission->name }}&nbsp;
                                                @endforeach
                                                @else
                                                None
                                                @endif
                                                @else
                                                All Permissions
                                                @endif
                                            </div>
                                        </div>
                                        <!--card-->
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card bg-secondary">
                                    <div class="card-header">
                                        @lang('Permissions')
                                    </div>
                                    <div class="card-body">
                                        @if ($permissions->count())
                                        @foreach($permissions as $permission)
                                        <div class="custom-control custom-checkbox">
                                            {{ html()->checkbox('permissions[]', in_array($permission->name, $userPermissions), $permission->name)->class('custom-control-input')->id('permission-'.$permission->id) }}
                                            {{ html()->label($permission->name)->class('custom-control-label')->for('permission-'.$permission->id) }}
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            {{ html()->submit($text = icon('fas fa-save')." Save")->class('btn btn-primary') }}
                        </div>
                    </div>

                    <div class="col-sm-8">
                        <div class="float-right">
                            @if ($$module_name_singular->status != 2 && $$module_name_singular->id != 1)
                            <a href="{{route('backend.users.block', $$module_name_singular)}}" class="btn btn-danger"
                                data-method="PATCH" data-token="{{csrf_token()}}" data-toggle="tooltip"
                                title="{{__('labels.backend.block')}}" data-confirm="Are you sure?"><i
                                    class="fas fa-ban"></i></a>
                            @endif
                            @if ($$module_name_singular->status == 2)
                            <a href="{{route('backend.users.unblock', $$module_name_singular)}}" class="btn btn-info"
                                data-method="PATCH" data-token="{{csrf_token()}}" data-toggle="tooltip"
                                title="{{__('labels.backend.unblock')}}" data-confirm="Are you sure?"><i
                                    class="fas fa-check"></i> Unblock</a>
                            @endif
                            @if ($$module_name_singular->email_verified_at == null)
                            <a href="{{route('backend.users.emailConfirmationResend', $$module_name_singular->id)}}"
                                class="btn btn-primary" data-toggle="tooltip" title="Send Confirmation Email"><i
                                    class="fas fa-envelope"></i></a>
                            @endif
                            @if($$module_name_singular->id != 1)
                            <a href="{{route("backend.$module_name.destroy", $$module_name_singular)}}"
                                class="btn btn-danger" data-method="DELETE" data-token="{{csrf_token()}}"
                                data-toggle="tooltip" title="{{__('labels.backend.delete')}}"><i
                                    class="fas fa-trash-alt"></i> Delete</a>
                            @endif
                            <a href="{{ route("backend.$module_name.index") }}" class="btn btn-warning"
                                data-toggle="tooltip" title="{{__('labels.backend.cancel')}}"><i
                                    class="fas fa-reply"></i> Cancel</a>
                        </div>
                    </div>
                </div>
                {{ html()->closeModelForm() }}
            </div>
            <!--/.col-->
        </div>
        <!--/.row-->
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col">
                <small class="float-right text-muted">
                    Updated: {{$user->updated_at->diffForHumans()}},
                    Created at: {{$user->created_at->isoFormat('LLLL')}}
                </small>
            </div>
        </div>
    </div>
</div>

@endsection