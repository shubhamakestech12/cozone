<div class="contents" id="top">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Property Enterprise</h4>
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-element">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-default card-md mb-4">
                        <div class="card-header">
                            <h6>Property Membership</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <form id="frm_property_enterprise">
                                        <input type="hidden" name="_token" value="">       
                                               <input type="hidden" name="id" id="id">
                                        <div class="form-row mx-n15">
                                            <div class="col-md-6 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Select Property</label>
                                                <select class="form-control" name="property" id="property">
                                                    <option value="">Select Property</option>
                                                    @if(!@empty($properties) and count($properties)>0)
                                                        @foreach($properties as $properties)
                                                            <option value="{{$properties->id}}">{{$properties->space_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div> 
                                            <div class="col-md-6 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Select Plan</label>
                                                <select class="form-control" name="plan" id="plan">
                                                    <option value="">Select Plan</option>
                                                    @if(!@empty($plans) and count($plans))
                                                        @foreach($plans as $plan)
                                                            <option value="{{$plan->id}}">{{$plan->plan_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>        
                                            <div class="col-md-6 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Price</label>
                                                <input type="number" class="form-control ih-medium ip-light radius-xs b-light" placeholder="Enter Price" name="price" id="price">
                                            </div>
                                           
                                            <div class="col-md-6 mb-20 px-15">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Select Amenties</label>
                                                        @if(!empty($amenties) and count($amenties)>0)
                                                        @foreach(@$amenties as $amenty)
                                                        <input type="checkbox" value="{{$amenty->name}}" class="ih-medium ip-light radius-xs b-light mx-3" name="amenties[]" id="amenties">{{strToUpper($amenty->name)}}
                                                        @endforeach
                                                    @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                        <button class="btn btn-xs btn-primary  float-right " type="submit" id="button">Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ends: .card -->
                </div>
                <div class="col-lg-12 ">
                    <div class="card mb-25">
                        <div class="card-header">
                            <h6>Property Membership List</h6>
                        </div>
                        <div class="card-body pt-0 pb-0">
                            <div class="drag-drop-wrap">
                                <div class="table-responsive table-revenue w-100 mb-30">
                                    <table class="table mb-0 table-basic" id="property_enterprise_datatable" width="100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Property Name</th>
                                                <th>Plan</th>
                                                <th>Price</th>
                                                <th>Amenties</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
                <!-- ends: card -->
            </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>

