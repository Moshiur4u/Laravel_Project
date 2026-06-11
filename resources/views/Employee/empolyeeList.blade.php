@extends('dashboard-blank')
@section('content')
    <div class="card radius-10">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <div>
                    <h4 class="mb-0">Empolyee List</h4>
                </div>
                <div class="dropdown ms-auto">
                    <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i
                            class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="javascript:;">Action</a>
                        </li>
                        <li><a class="dropdown-item" href="javascript:;">Another action</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Photo</th>
                            <th>Product ID</th>
                            <th>Status</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Iphone 5</td>
                            <td><img src="{{ asset('assets/images/products/01.png') }}" class="product-img-2"
                                    alt="product img"></td>
                            <td>#9405822</td>
                            <td><span class="text-white shadow-sm badge bg-gradient-quepal w-100">Paid</span>
                            </td>
                            <td>$1250.00</td>
                        </tr>

                        <tr>
                            <td>Earphone GL</td>
                            <td><img src="{{ asset('assets/images/products/02.png') }}" class="product-img-2"
                                    alt="product img"></td>
                            <td>#8304620</td>
                            <td><span class="text-white shadow-sm badge bg-gradient-blooker w-100">Pending</span>
                            </td>
                            <td>$1500.00</td>
                        </tr>

                        <tr>
                            <td>HD Hand Camera</td>
                            <td><img src="{{ asset('assets/images/products/03.png') }}" class="product-img-2"
                                    alt="product img"></td>
                            <td>#4736890</td>
                            <td><span class="text-white shadow-sm badge bg-gradient-bloody w-100">Failed</span>
                            </td>
                            <td>$1400.00</td>
                        </tr>

                        <tr>
                            <td>Clasic Shoes</td>
                            <td><img src="{{ asset('assets/images/products/04.png') }}" class="product-img-2"
                                    alt="product img"></td>
                            <td>#8543765</td>
                            <td><span class="text-white shadow-sm badge bg-gradient-quepal w-100">Paid</span>
                            </td>
                            <td>$1200.00</td>
                        </tr>
                        <tr>
                            <td>Sitting Chair</td>
                            <td><img src="{{ asset('assets/images/products/06.png') }}" class="product-img-2"
                                    alt="product img"></td>
                            <td>#9629240</td>
                            <td><span class="text-white shadow-sm badge bg-gradient-blooker w-100">Pending</span>
                            </td>
                            <td>$1500.00</td>
                        </tr>
                        <tr>
                            <td>Hand Watch</td>
                            <td><img src="{{ asset('assets/images/products/05.png') }}" class="product-img-2"
                                    alt="product img"></td>
                            <td>#8506790</td>
                            <td><span class="text-white shadow-sm badge bg-gradient-bloody w-100">Failed</span>
                            </td>
                            <td>$1800.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
