@extends('master_dashboard')
@section('title')
    Dashboard
@endsection

@section('content-title')
<span class="page-title-icon bg-gradient-primary text-white me-2">
    <i class="mdi mdi-home"></i>
  </span> Dashboard
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">
    <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
  </li>
@endsection

@section('content-body')
<div class="col-md-4 stretch-card grid-margin">
    <div class="card bg-gradient-danger card-img-holder text-white">
      <div class="card-body">
        <img src="{{('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
        <h4 class="font-weight-normal mb-3">Weekly Sales <i class="mdi mdi-chart-line mdi-24px float-end"></i>
        </h4>
        <h2 class="mb-5">$ 15,0000</h2>
        <h6 class="card-text">Increased by 60%</h6>
      </div>
    </div>
  </div>
  <div class="col-md-4 stretch-card grid-margin">
    <div class="card bg-gradient-info card-img-holder text-white">
      <div class="card-body">
        <img src="{{('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
        <h4 class="font-weight-normal mb-3">Weekly Orders <i class="mdi mdi-bookmark-outline mdi-24px float-end"></i>
        </h4>
        <h2 class="mb-5">45,6334</h2>
        <h6 class="card-text">Decreased by 10%</h6>
      </div>
    </div>
  </div>
  <div class="col-md-4 stretch-card grid-margin">
    <div class="card bg-gradient-success card-img-holder text-white">
      <div class="card-body">
        <img src="{{('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
        <h4 class="font-weight-normal mb-3">Visitors Online <i class="mdi mdi-diamond mdi-24px float-end"></i>
        </h4>
        <h2 class="mb-5">95,5741</h2>
        <h6 class="card-text">Increased by 5%</h6>
      </div>
    </div>
  </div>

@endsection
