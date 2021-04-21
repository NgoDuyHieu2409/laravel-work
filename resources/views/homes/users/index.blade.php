<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hidden sm:rounded-lg">
            <div class="row">
                <div class="col-12 mb-2">
                    <a href="{{ route('dashboard') }}" title="Back to Dashboard" class=""><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header header-elements-inline">
                            <h4 class="card-title"><b>Thông tin chung</b></h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <img src="{{ $user->profile_photo_url ?? '' }}" alt="{{ $user->name }}" class="rounded-full h-40 w-40 object-cover">
                                </div>
                                <div class="col-sm-9">
                                    @include('homes.users.items.contact-information')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const COMPANY_PREF = @JSON($user->congact->pref ?? 0);
        </script>
    @endpush
</x-app-layout>
