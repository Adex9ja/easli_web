@extends('template')
@section('content')

    <div class="row chat-wrapper">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row position-relative">
                        <div class="col-lg-4 chat-aside border-lg-right">
                            <div class="aside-content">
                                <div class="aside-header">
                                    <div class="d-flex justify-content-between align-items-center pb-2 mb-2">
                                        <div class="d-flex align-items-center">
                                            <figure class="mr-2 mb-0">
                                                <img src="{{ asset('images/default_profile.png') }}" class="img-sm rounded-circle" alt="profile">
                                                <div class="status online"></div>
                                            </figure>
                                            <div>
                                                <h6>{{ \Illuminate\Support\Facades\Auth::user()->fullname }}</h6>
                                                <p class="text-muted tx-13">{{ \Illuminate\Support\Facades\Auth::user()->user_role }}</p>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="icon-lg text-muted pb-3px" data-feather="settings" data-toggle="tooltip" title="Settings"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item d-flex align-items-center" href="{{ url()->to('profile') }}"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit Profile</span></a>
                                                <a class="dropdown-item d-flex align-items-center" href="{{ url()->route('logout') }}"><i data-feather="log-out" class="icon-sm mr-2"></i> <span class="">Switch User</span></a>
                                            </div>
                                        </div>
                                    </div>
                                    <form class="search-form">
                                        <div class="input-group border rounded-sm">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text border-0 rounded-sm">
                                                    <i data-feather="search" class="icon-md cursor-pointer"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control  border-0 rounded-sm" id="searchForm" placeholder="Search here...">
                                        </div>
                                    </form>
                                </div>
                                <div class="aside-body">
                                    <ul class="nav nav-tabs mt-3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="chats-tab" data-toggle="tab" href="#chats" role="tab" aria-controls="chats" aria-selected="true">
                                                <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                                                    <i data-feather="message-square" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                                                    <p class="d-none d-sm-block">Contact-Us Messages</p>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content mt-3">
                                        <div class="tab-pane fade show active" id="chats" role="tabpanel" aria-labelledby="chats-tab">
                                            <div>
                                                <ul class="list-unstyled chat-list px-1">
                                                    @foreach($messages as $message)
                                                        <li class="chat-item @if($message->status == 0) bg-gradient-light @endif pr-1">
                                                            <a href="/messages/list/{{ $message->contact_id }}" class="d-flex align-items-center">
                                                                <figure class="mb-0 mr-2">
                                                                    <img src="{{ asset('images/default_profile.png') }}" class="img-xs rounded-circle" alt="user">
                                                                </figure>
                                                                <div class="d-flex justify-content-between flex-grow border-bottom">
                                                                    <div>
                                                                        <p class="text-body font-weight-bold">{{ $message->fullname }}</p>
                                                                        <p class="text-muted tx-13">{{ substr($message->message, 0, 30) }}</p>
                                                                    </div>
                                                                    <div class="d-flex flex-column align-items-end">
                                                                        <p class="text-muted tx-13 mb-1">{{ explode(' ', $message->created_at)[0] }}</p>
                                                                        <div class="text-small">{{ explode(' ', $message->created_at)[1] }}</div>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(isset($messageDetail))
                            <div class="col-lg-8 chat-content">
                                <div class="chat-header border-bottom pb-2">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i data-feather="corner-up-left" id="backToChatList" class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
                                            <figure class="mb-0 mr-2">
                                                <img src="{{ asset('images/default_profile.png') }}" class="img-sm rounded-circle" alt="image">
                                            </figure>
                                            <div>
                                                <p>{{ $messageDetail->fullname }}</p>
                                                <p class="text-muted tx-13">{{ $messageDetail->user_role }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mr-n1">
                                            <a href="mailto://{{ $messageDetail->email }}">
                                                <i data-feather="mail" class="icon-lg text-muted mr-3" data-toggle="tooltip" title="Send Mail"></i>
                                            </a>
                                            <a href="tel://{{ $messageDetail->phoneno }}">
                                                <i data-feather="phone-call" class="icon-lg text-muted mr-0 mr-sm-3" data-toggle="tooltip" title="Start voice call"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat-body">
                                    <p>{{ $messageDetail->message }}</p>
                                    <sub>{{ $messageDetail->created_at }}</sub>
                                </div>

                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
