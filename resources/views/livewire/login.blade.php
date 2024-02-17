<div class="page-header min-vh-75">
    @section('title', 'داخل شدن')
<div class="container">
        <div class="row">
            <div class="col-xl-5 col-lg-6 col-md-7 d-flex flex-column mx-auto">
                <div class="card card-plain mt-8 bg-white p-4">
                    <div class="card-header pb-0 text-left bg-transparent">
                        <h3 class="font-weight-bolder text-primary text-gradient">خوش آمدید</h3>
                        <p class="mb-0">
                            نام کاربری و پسورد تان را وارد کنید.
                        </p>
                    </div>
                    <div class="card-body">
                        <form role="form" wire:submit.prevent="login" >
                            <label>نام کاربری</label>
                            <div class="mb-3">
                                <input wire:model.defer="data.name" type="text" class="form-control" placeholder="نام را وارد کنید." aria-label="username" aria-describedby="username">
                                @error('name')
                                <div class="text-danger small">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <label>پسورد</label>
                            <div class="mb-3">
                                <input wire:model.defer="data.password" type="password" class="form-control" placeholder="پسورد را وارد کنید." aria-label="Password" aria-describedby="password-addon">
                                @error('password')
                                <div class="text-danger small">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn bg-gradient-primary w-100 mt-4 mb-0">
                                    داخل شدن
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
