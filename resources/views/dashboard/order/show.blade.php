@extends('layouts.master')

@section('title', __('Order Details'))

@section('css')
    <style>
        .edit-loader {
            width: 100%;
        }

        .edit-loader .sk-chase {
            display: block;
            margin: 0 auto;
        }
    </style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.orders.index') }}">{{ __('Orders') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Details') }}</li>
@endsection
@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
            <div class="d-flex flex-column justify-content-center">
                <div class="mb-1">
                    <span class="h5">Order #{{ $odr->orderID }} </span>
                    @php
                        $paymentStatusMap = [
                            1 => ['label' => 'Pending', 'class' => 'warning'],
                            2 => ['label' => 'Success', 'class' => 'success'],
                            3 => ['label' => 'Failed', 'class' => 'danger'],
                            4 => ['label' => 'Hold', 'class' => 'secondary'],
                        ];
                        $paymentStatus = $paymentStatusMap[$odr->payment_status] ?? [
                            'label' => 'Unknown',
                            'class' => 'dark',
                        ];
                    @endphp
                    <span class="badge bg-label-{{ $paymentStatus['class'] }} me-1 ms-2">{{ $paymentStatus['label'] }}</span>
                    @php
                        $orderStatusMap = [
                            1 => ['label' => 'Under Review', 'class' => 'warning'],
                            2 => ['label' => 'Design Approved', 'class' => 'success'],
                            3 => ['label' => 'Waiting for Garments', 'class' => 'info'],
                            4 => ['label' => 'Sent to Graphic Designer', 'class' => 'primary'],
                            5 => ['label' => 'In Production', 'class' => 'secondary'],
                        ];
                        $orderStatus = $orderStatusMap[$odr->order_status] ?? ['label' => 'Unknown', 'class' => 'dark'];
                    @endphp
                    <span class="badge bg-label-{{ $orderStatus['class'] }}">{{ $orderStatus['label'] }}</span>
                </div>
                <p class="mb-0">{{ $odr->created_at->format('d F, Y h:i A') }}</p>
            </div>
        </div>

        <!-- Order Details Table -->
        <!-- Navbar pills -->
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="row">
                    <div class="col-12 col-lg-12">
                        <div class="nav-align-top">
                            <ul class="nav nav-pills flex-column flex-sm-row mb-6 gap-2 gap-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link profile-tab" href="#" data-target="#order-details-section"
                                        data-query="order-details">
                                        <i class="ti-sm ti ti-user-check me-1_5"></i>
                                        {{ __('Order Details') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link profile-tab" href="#" data-target="#logo-section"
                                        data-query="logo">
                                        <i class="ti-sm ti ti-settings me-1_5"></i>
                                        {{ __('Logo') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link profile-tab" href="#" data-target="#product-with-logo-section"
                                        data-query="product-with-logo">
                                        <i class="ti-sm ti ti-lock me-1_5"></i>
                                        {{ __('Display Product with Logo') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="navpills-items col-12 col-lg-12">
                        <div class="edit-loader">
                            <div class="sk-chase sk-primary">
                                <div class="sk-chase-dot"></div>
                                <div class="sk-chase-dot"></div>
                                <div class="sk-chase-dot"></div>
                                <div class="sk-chase-dot"></div>
                                <div class="sk-chase-dot"></div>
                                <div class="sk-chase-dot"></div>
                            </div>
                        </div>
                        <div id="order-details-section" style="display: none;">
                            @include('dashboard.order.sections.order-details')
                        </div>
                        <div id="logo-section" style="display: none;">
                            @include('dashboard.order.sections.logo')
                        </div>
                        <div id="product-with-logo-section" style="display: none;">
                            @include('dashboard.order.sections.product-with-logo')
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title m-0">Upload Image</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dashboard.orders.update', $odr->id) }}" class="post-job-form" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="product_photos" class="form-label">Upload image
                                    with logo:</label>
                                <input type="file" name="image[]" multiple
                                    class="form-control @error('image') is-invalid @enderror" id="id_product_photos"
                                    accept="image/png, image/jpg, image/jpeg">
                                <small class="form-text text-muted">You can upload multiple images (PNG, JPG,
                                    JPEG).</small>

                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end">
                                <button type="submit" id="jobPostSubmitBtn" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title m-0">Update Status</h5>
                    </div>
                    <div class="card-body">
                        <select class="form-select select2" id="orderStatus" data-order-id="{{ $odr->id }}">
                            <option value="" disabled>Select Status</option>

                            @foreach ($status as $key => $st)
                                <option value="{{ $key }}"
                                    {{ isset($odr) && $odr->order_status == $key ? 'selected' : '' }}>
                                    {{ $st }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>

                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title m-0">Shipping Information</h5>
                    </div>
                    <div class="card-body">
                        @if ($odr->shipping != 2)
                            <p class="mb-2"><span style="font-weight: 600;">Name : </span>{{ $odr->billing_name ?? '' }}
                            </p>
                            <p class="mb-2"><span style="font-weight: 600;">Company Name :
                                </span>{{ $odr->company_name ?? '' }}</p>
                            <p class="mb-2"><span style="font-weight: 600;">Email : </span>{{ $odr->billing_email }}</p>
                            <p class="mb-2"><span style="font-weight: 600;">Phone : </span>{{ $odr->billing_mobile }}</p>
                            <p class="mb-2"><span style="font-weight: 600;">Address : </span>
                                @if (isset($odr->address))
                                    {{ $odr->address }} , {{ $odr->town }} ,{{ $odr->country }} ,
                                    {{ $odr->pincode }}
                                @endif
                            </p>
                        @else
                            <p class="mb-2"><span style="font-weight: 600;">Name : </span>{{ $odr->shipping_name ?? '' }}
                            </p>
                            <p class="mb-2"><span style="font-weight: 600;">Company Name :
                                </span>{{ $odr->shipping_company ?? '' }}</p>
                            <p class="mb-2"><span style="font-weight: 600;">Email : </span>{{ $odr->shipping_email }}</p>
                            <p class="mb-2"><span style="font-weight: 600;">Phone : </span>{{ $odr->shipping_mobile }}
                            </p>
                            <p class="mb-2"><span style="font-weight: 600;">Address : </span>
                                @if (isset($odr->shipping_address))
                                    {{ $odr->shipping_address }} , {{ $odr->shipping_town }}
                                    ,{{ $odr->shipping_country }} ,
                                    {{ $odr->shipping_pincode }}
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!--/ Navbar pills -->

        <!-- Edit User Modal -->
        <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-6">
                            <h4 class="mb-2">Edit User Information</h4>
                            <p>Updating user details will receive a privacy audit.</p>
                        </div>
                        <form id="editUserForm" class="row g-6" onsubmit="return false">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditUserFirstName">First Name</label>
                                <input type="text" id="modalEditUserFirstName" name="modalEditUserFirstName"
                                    class="form-control" placeholder="John" value="John" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditUserLastName">Last Name</label>
                                <input type="text" id="modalEditUserLastName" name="modalEditUserLastName"
                                    class="form-control" placeholder="Doe" value="Doe" />
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="modalEditUserName">Username</label>
                                <input type="text" id="modalEditUserName" name="modalEditUserName"
                                    class="form-control" placeholder="johndoe007" value="johndoe007" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditUserEmail">Email</label>
                                <input type="text" id="modalEditUserEmail" name="modalEditUserEmail"
                                    class="form-control" placeholder="example@domain.com" value="example@domain.com" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditUserStatus">Status</label>
                                <select id="modalEditUserStatus" name="modalEditUserStatus" class="select2 form-select"
                                    aria-label="Default select example">
                                    <option selected>Status</option>
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                    <option value="3">Suspended</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditTaxID">Tax ID</label>
                                <input type="text" id="modalEditTaxID" name="modalEditTaxID"
                                    class="form-control modal-edit-tax-id" placeholder="123 456 7890"
                                    value="123 456 7890" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditUserPhone">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text">US (+1)</span>
                                    <input type="text" id="modalEditUserPhone" name="modalEditUserPhone"
                                        class="form-control phone-number-mask" placeholder="202 555 0111"
                                        value="202 555 0111" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditUserLanguage">Language</label>
                                <select id="modalEditUserLanguage" name="modalEditUserLanguage"
                                    class="select2 form-select" multiple>
                                    <option value="">Select</option>
                                    <option value="english" selected>English</option>
                                    <option value="spanish">Spanish</option>
                                    <option value="french">French</option>
                                    <option value="german">German</option>
                                    <option value="dutch">Dutch</option>
                                    <option value="hebrew">Hebrew</option>
                                    <option value="sanskrit">Sanskrit</option>
                                    <option value="hindi">Hindi</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditUserCountry">Country</label>
                                <select id="modalEditUserCountry" name="modalEditUserCountry" class="select2 form-select"
                                    data-allow-clear="true">
                                    <option value="">Select</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Bangladesh">Bangladesh</option>
                                    <option value="Belarus">Belarus</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="Canada">Canada</option>
                                    <option value="China">China</option>
                                    <option value="France">France</option>
                                    <option value="Germany">Germany</option>
                                    <option value="India" selected>India</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Korea">Korea, Republic of</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="Russia">Russian Federation</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States">United States</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="editBillingAddress" />
                                    <label for="editBillingAddress" class="switch-label">Use as a billing address?</label>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary me-3">Submit</button>
                                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Edit User Modal -->

        <!-- Add New Address Modal -->
        <div class="modal fade" id="addNewAddress" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-6">
                            <h4 class="address-title mb-2">Add New Address</h4>
                            <p class="address-subtitle">Add new address for express delivery</p>
                        </div>
                        <form id="addNewAddressForm" class="row g-6" onsubmit="return false">
                            <div class="col-12 form-control-validation">
                                <div class="row">
                                    <div class="col-md mb-md-0 mb-4">
                                        <div class="form-check custom-option custom-option-icon">
                                            <label class="form-check-label custom-option-content" for="customRadioHome">
                                                <span class="custom-option-body">
                                                    <svg width="28" height="28" viewBox="0 0 28 28"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path opacity="0.2"
                                                            d="M16.625 23.625V16.625H11.375V23.625H4.37501V12.6328C4.37437 12.5113 4.39937 12.391 4.44837 12.2798C4.49737 12.1686 4.56928 12.069 4.65939 11.9875L13.4094 4.03592C13.5689 3.88911 13.7778 3.80762 13.9945 3.80762C14.2113 3.80762 14.4202 3.88911 14.5797 4.03592L23.3406 11.9875C23.4287 12.0706 23.4992 12.1706 23.548 12.2814C23.5969 12.3922 23.6231 12.5117 23.625 12.6328V23.625H16.625Z" />
                                                        <path
                                                            d="M23.625 23.625V12.6328C23.623 12.5117 23.5969 12.3922 23.548 12.2814C23.4992 12.1706 23.4287 12.0706 23.3406 11.9875L14.5797 4.03592C14.4202 3.88911 14.2113 3.80762 13.9945 3.80762C13.7777 3.80762 13.5689 3.88911 13.4094 4.03592L4.65937 11.9875C4.56926 12.069 4.49736 12.1686 4.44836 12.2798C4.39936 12.391 4.37436 12.5113 4.375 12.6328V23.625M1.75 23.625H26.25M16.625 23.625V17.5C16.625 17.2679 16.5328 17.0454 16.3687 16.8813C16.2046 16.7172 15.9821 16.625 15.75 16.625H12.25C12.0179 16.625 11.7954 16.7172 11.6313 16.8813C11.4672 17.0454 11.375 17.2679 11.375 17.5V23.625"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                    <span class="custom-option-title">Home</span>
                                                    <small> Delivery time (9am – 9pm) </small>
                                                </span>
                                                <input name="customRadioIcon" class="form-check-input" type="radio"
                                                    value="" id="customRadioHome" checked />
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md mb-md-0 mb-4">
                                        <div class="form-check custom-option custom-option-icon">
                                            <label class="form-check-label custom-option-content" for="customRadioOffice">
                                                <span class="custom-option-body">
                                                    <svg width="28" height="28" viewBox="0 0 28 28"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path opacity="0.2"
                                                            d="M15.75 23.625V4.375C15.75 4.14294 15.6578 3.92038 15.4937 3.75628C15.3296 3.59219 15.1071 3.5 14.875 3.5H4.375C4.14294 3.5 3.92038 3.59219 3.75628 3.75628C3.59219 3.92038 3.5 4.14294 3.5 4.375V23.625" />
                                                        <path
                                                            d="M1.75 23.625H26.25M15.75 23.625V4.375C15.75 4.14294 15.6578 3.92038 15.4937 3.75628C15.3296 3.59219 15.1071 3.5 14.875 3.5H4.375C4.14294 3.5 3.92038 3.59219 3.75628 3.75628C3.59219 3.92038 3.5 4.14294 3.5 4.375V23.625M24.5 23.625V11.375C24.5 11.1429 24.4078 10.9204 24.2437 10.7563C24.0796 10.5922 23.8571 10.5 23.625 10.5H15.75M7 7.875H10.5M8.75 14.875H12.25M7 19.25H10.5M19.25 19.25H21M19.25 14.875H21"
                                                            stroke-opacity="0.9" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                    <span class="custom-option-title"> Office </span>
                                                    <small> Delivery time (9am – 5pm) </small>
                                                </span>
                                                <input name="customRadioIcon" class="form-check-input" type="radio"
                                                    value="" id="customRadioOffice" />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 form-control-validation col-md-6">
                                <label class="form-label" for="modalAddressFirstName">First Name</label>
                                <input type="text" id="modalAddressFirstName" name="modalAddressFirstName"
                                    class="form-control" placeholder="John" />
                            </div>
                            <div class="col-12 form-control-validation col-md-6">
                                <label class="form-label" for="modalAddressLastName">Last Name</label>
                                <input type="text" id="modalAddressLastName" name="modalAddressLastName"
                                    class="form-control" placeholder="Doe" />
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="modalAddressCountry">Country</label>
                                <select id="modalAddressCountry" name="modalAddressCountry" class="select2 form-select"
                                    data-allow-clear="true">
                                    <option value="">Select</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Bangladesh">Bangladesh</option>
                                    <option value="Belarus">Belarus</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="Canada">Canada</option>
                                    <option value="China">China</option>
                                    <option value="France">France</option>
                                    <option value="Germany">Germany</option>
                                    <option value="India">India</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Korea">Korea, Republic of</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="Russia">Russian Federation</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States">United States</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="modalAddressAddress1">Address Line 1</label>
                                <input type="text" id="modalAddressAddress1" name="modalAddressAddress1"
                                    class="form-control" placeholder="12, Business Park" />
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="modalAddressAddress2">Address Line 2</label>
                                <input type="text" id="modalAddressAddress2" name="modalAddressAddress2"
                                    class="form-control" placeholder="Mall Road" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalAddressLandmark">Landmark</label>
                                <input type="text" id="modalAddressLandmark" name="modalAddressLandmark"
                                    class="form-control" placeholder="Nr. Hard Rock Cafe" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalAddressCity">City</label>
                                <input type="text" id="modalAddressCity" name="modalAddressCity" class="form-control"
                                    placeholder="Los Angeles" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalAddressLandmark">State</label>
                                <input type="text" id="modalAddressState" name="modalAddressState"
                                    class="form-control" placeholder="California" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalAddressZipCode">Zip Code</label>
                                <input type="text" id="modalAddressZipCode" name="modalAddressZipCode"
                                    class="form-control" placeholder="99950" />
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="billingAddress" />
                                    <label for="billingAddress" class="form-switch-label">Use as a billing
                                        address?</label>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary me-3">Submit</button>
                                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Add New Address Modal -->
    </div>
    <!-- / Content -->
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            function activateTabFromURL() {
                var urlParams = new URLSearchParams(window.location.search);
                var activeTab = urlParams.get('tab') || 'order-details'; // Default to 'company'

                var tabMapping = {
                    'order-details': '#order-details-section',
                    'logo': '#logo-section',
                    'product-with-logo': '#product-with-logo-section',
                };

                var activeTabSelector = tabMapping[activeTab] || '#order-details-section';

                $('.navpills-items > div').hide(); // Hide all sections initially

                setTimeout(function() {
                    $('.edit-loader').fadeOut(); // Hide loader
                    $(activeTabSelector).fadeIn(); // Show the selected section
                    $('a.profile-tab').removeClass('active');
                    $('a[data-target="' + activeTabSelector + '"]').addClass('active');
                }, 100); // 1-second delay to simulate loading effect
            }

            activateTabFromURL(); // Load the correct tab immediately on page load

            $('a.profile-tab').on('click', function(e) {
                e.preventDefault();

                $('.edit-loader').fadeIn(); // Show loader on tab switch
                $('a.profile-tab').removeClass('active');
                $('.navpills-items > div').hide();

                $(this).addClass('active');

                var target = $(this).data('target');
                var queryValue = $(this).data('query');

                setTimeout(function() {
                    $('.edit-loader').fadeOut();
                    $(target).fadeIn();
                }, 100); // Shorter delay for smoother experience

                var newURL = window.location.pathname + '?tab=' + queryValue;
                window.history.pushState({
                    path: newURL
                }, '', newURL);
            });

            window.addEventListener('popstate', activateTabFromURL);

            $('#orderStatus').on('change', function() {
                let status = $(this).val();
                let orderId = $(this).data('order-id');

                $.ajax({
                    url: '{{ route('dashboard.orders.status-update') }}',
                    method: 'POST',
                    data: {
                        order_id: orderId,
                        order_status: status,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    },

                    error: function(error) {
                        Swal.fire({
                            title: 'Error!',
                            text: "Something went wrong! Please try again later",
                            icon: 'error',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                })
            })
        });
    </script>
@endsection
