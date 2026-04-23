@extends('backend.app')
@section('title', 'User Profile')
@push('style')
<style>
    .social-profile .social-img-wrap {
        position: relative;
        width: 130px;
        margin: 20px auto;
    }

    .social-profile .social-img {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        overflow: hidden;
        border: 5px solid #fff;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .social-profile .social-img:hover {
        transform: scale(1.03);
    }

    .social-profile .social-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .social-profile .active-badge {
        position: absolute;
        bottom: 8px;
        right: 8px;
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #00ca72 0%, #00ab55 100%);
        border-radius: 50%;
        border: 3px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 16px;
        box-shadow: 0 4px 15px rgba(0, 171, 85, 0.4);
        z-index: 5;
        animation: active-pulse 2s infinite;
    }

    @keyframes active-pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(0, 202, 114, 0.7);
        }

        70% {
            box-shadow: 0 0 0 12px rgba(0, 202, 114, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(0, 202, 114, 0);
        }
    }

    .card.social-profile {
        border-radius: 30px;
        overflow: hidden;
        border: none;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        background: #fff;
    }

    .social-details h5 a {
        color: #1a202c;
        font-weight: 800;
        font-size: 22px;
        text-decoration: none;
    }

</style>
@endpush
@section('page-content')

<x-breadcrumbs title="User Profile" subtitle="Details" />

<div class="container-fluid">
    <div class="row justify-content-left">
        <div class="col-xl-6 col-md-8">
            <div class="card social-profile">
                <div class="card-body text-center p-5">
                    <div class="social-img-wrap">
                        <div class="social-img">
                            <img src="{{ $user->avatar ? asset($user->avatar) : asset('backend/assets/images/user/3.jpg') }}" alt="{{ $user->name }}">
                        </div>
                        @if($user->is_active)
                        <div class="active-badge shadow-lg">
                            <i class="fa fa-check"></i>
                        </div>
                        @endif
                    </div>
                    <div class="social-details">
                        <h5 class="mb-1"><a href="social-app.html">{{ $user->name }}</a></h5><span class="f-light">{{ $user->email }}</span>
                        <ul class="card-social">
                            <li><a href="{{  $user->facebook_url ? $user->facebook_url : 'https://www.facebook.com/' }}" target="_blank"><i class="fa fa-facebook"></i></a></li>

                            <li><a href="{{ $user->google_plus_url ? $user->google_plus_url : 'https://plus.google.com/' }}" target="_blank"><i class="fa fa-google-plus"></i></a></li>

                            <li><a href="{{ $user->twitter_url ? $user->twitter_url : 'https://twitter.com/' }}" target="_blank"><i class="fa fa-twitter"></i></a></li>

                            <li><a href="{{ $user->instagram_url ? $user->instagram_url : 'https://www.instagram.com/' }}" target="_blank"><i class="fa fa-instagram"></i></a></li>

                            <li><a href="{{ $user->rss_url ? $user->rss_url : 'https://rss.app/' }}" target="_blank"><i class="fa fa-rss"></i></a></li>

                        </ul>
                        <ul class="social-follow">
                            <li>
                                <h5 class="mb-0">1,908</h5><span class="f-light">Posts</span>
                            </li>
                            <li>
                                <h5 class="mb-0">34.0k</h5><span class="f-light">Followers</span>
                            </li>
                            <li>
                                <h5 class="mb-0">897</h5><span class="f-light">Following</span>
                            </li>
                        </ul>

                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="col-xl-7 col-md-4">
            <div class="card">
                <div class="card-header card-no-border">
                    <div class="header-top">
                        <h4>Last Orders </h4>
                        <div class="dropdown icon-dropdown setting-menu">
                            <button class="btn dropdown-toggle" id="userdropdown3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                </svg>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdown3"><a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item" href="#">Monthly </a><a class="dropdown-item" href="#">Yearly</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive custom-scrollbar">
                        <table class="last-orders-table table" id="last-orders">
                            <thead>
                                <tr>
                                    <th>Name </th>
                                    <th>Order No. </th>
                                    <th>Amount</th>
                                    <th>Payment Type </th>
                                    <th>Date</th>
                                    <th>Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="user-data">
                                            <div><img src="{{ asset('backend/assets/images/dashboard/avtar/2.jpg') }}" alt="avatar">
    </div>
    <div> <a href="user-profile.html">
            <h4>Dmitriy Groshev</h4>
        </a><span>Switzerland</span></div>
</div>
</td>
<td>#790841</td>
<td>$2.499</td>
<td>Credit Card</td>
<td>1 Oct, 14:43</td>
<td>
    <div class="dropdown icon-dropdown">
        <button class="btn dropdown-toggle" id="userdropdownes4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="drop-menu"><i class="icon-more-alt"></i></div>
        </button>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdownes4"><a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item" href="#">Monthly</a><a class="dropdown-item" href="#">Yearly</a></div>
    </div>
</td>
</tr>
<tr>
    <td>
        <div class="user-data">
            <div><img src="{{ asset('backend/assets/images/dashboard/avtar/17.jpg') }}" alt="avatar">
            </div>
            <div><a href="user-profile.html">
                    <h4>Patrick Beverley</h4>
                </a><span> Germany</span></div>
        </div>
    </td>
    <td>#454489</td>
    <td>$2.499</td>
    <td>Paypal</td>
    <td>30 Sep, 23:01</td>
    <td>
        <div class="dropdown icon-dropdown">
            <button class="btn dropdown-toggle" id="userdropdownes1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="drop-menu"><i class="icon-more-alt"></i></div>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdownes1"><a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item" href="#">Monthly</a><a class="dropdown-item" href="#">Yearly</a></div>
        </div>
    </td>
</tr>
<tr>
    <td>
        <div class="user-data">
            <div><img src="{{ asset('backend/assets/images/avtar/18.jpg') }}" alt="avatar"></div>
            <div><a href="user-profile.html">
                    <h4>Kevin Greem</h4>
                </a><span> Canada</span></div>
        </div>
    </td>
    <td>#594579</td>
    <td>$2.499</td>
    <td>Credit Card</td>
    <td>29 Sep,09:31</td>
    <td>
        <div class="dropdown icon-dropdown">
            <button class="btn dropdown-toggle" id="userdropdownes2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="drop-menu"><i class="icon-more-alt"></i></div>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdownes2"><a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item" href="#">Monthly</a><a class="dropdown-item" href="#">Yearly</a></div>
        </div>
    </td>
</tr>
<tr>
    <td>
        <div class="user-data">
            <div><img src="{{ asset('backend/assets/images/dashboard/avtar/19.jpg') }}" alt="avatar">
            </div>
            <div><a href="user-profile.html">
                    <h4>William Barton</h4>
                </a><span>United States</span></div>
        </div>
    </td>
    <td>#478495</td>
    <td>$2.499</td>
    <td>Credit Card</td>
    <td>28 Sep, 04:34</td>
    <td>
        <div class="dropdown icon-dropdown">
            <button class="btn dropdown-toggle" id="userdropdownes3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="drop-menu"><i class="icon-more-alt"></i></div>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdownes3"><a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item" href="#">Monthly</a><a class="dropdown-item" href="#">Yearly </a></div>
        </div>
    </td>
</tr>
</tbody>
</table>
</div>
</div>
</div>

</div> --}}

</div>

</div>
<!-- Container-fluid Ends-->
@endsection
