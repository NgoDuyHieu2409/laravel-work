<x-app-layout>
    {{-- <x-slot name="header">
        <form class="form-inline mr-3">
            <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </x-slot> --}}

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hidden sm:rounded-lg">
            <div class="row d-flex align-items-stretch">
                @foreach($works as $work)
                    <div class="col-12 col-sm-4 d-flex align-items-stretch">
                        <div class="card bg-white" style="width: 100%;">
                            <div class="card-header border-bottom-0 text-uppercase text-truncate" title="{{ $work->title }}">
                                <a href="{{ route('work.show', ['id' => $work->id]) }}">{{ $work->title }}</a>
                            </div>
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-12">
                                        <ul class="ml-2 mb-0 fa-ul">
                                            <li class="small">
                                                <i class="fas fa-lg fa-building"></i> 
                                                {{ $work->address}}
                                            </li>
                                            <li class="small">
                                                <i class="fas fa-lg fa-user"></i>
                                                {{ $work->contact_name}}
                                            </li>
                                            <li class="small">
                                                <i class="fas fa-lg fa-phone"></i>
                                                {{ $work->contact_tel}}
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- <div class="col-5 text-center">
                                        <img src="../../dist/img/user1-128x128.jpg" alt="user-avatar" class="img-circle img-fluid">
                                    </div>  -->
                                </div>
                                <hr class="hr-custom">
                                    
                                <div class="row" style="font-size: 12px;">
                                    <div class="col-3">
                                        <i class="fas fa-dollar-sign"></i> {{ $work->hourly_wage}}/giờ
                                    </div>
                                    <div class="col-5">
                                        <i class="far fa-address-book"></i> {{ $work->work_type}}
                                    </div>
                                    <div class="col-4">
                                        <i class="far fa-clock"></i> {{ date('d-m-Y', strtotime($work->worktime_start_at)) }}
                                    </div>
                                </div>
                            </div>
                            @auth
                            <div class="card-footer">
                                <div class="text-right">
                                    <a href="{{ route('work.show', ['id' => $work->id]) }}" class="btn btn-sm btn-success">Nộ hồ sơ</a>
                                </div>
                            </div>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
