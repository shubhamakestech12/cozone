<div class="contents" id="top">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Property Details</h4>
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
                            <h6>Property Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <form id="frm_property_details">
                                        <input type="hidden" name="_token" value="">       
                                               <input type="hidden" name="id" id="id">
                                        <div class="form-row mx-n15">
                                            <div class="col-md-6 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Select Property</label>
                                                <select class="form-control" name="property_name" id="property_name">
                                                    <option value="">Select Property</option>
                                                    @if(!@empty($property_names) and count($property_names))
                                                        @foreach($property_names as $property_name)
                                                            <option value="{{$property_name->id}}">{{$property_name->space_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>  
                                            
                                            <div class="col-md-6 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Area</label>
                                                <input type="text" class="form-control ih-medium ip-light radius-xs b-light" placeholder="Enter Area" name="area" id="area">
                                            </div>
                                            <div class="col-md-6 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Opening Time</label>
                                                <input type="time" class="form-control ih-medium ip-light radius-xs b-light" name="open_time" id="open_time">
                                            </div>
                                            <div class="col-md-6 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Closing Time</label>
                                                <input type="time" class="form-control ih-medium ip-light radius-xs b-light" name="close_time" id="close_time">
                                            </div>
                                            <div class="col-md-6 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">About Property</label>
                                                <textarea type="text" class="form-control ih-medium ip-light radius-xs b-light" name="about" id="about"></textarea>
                                            </div>
                                            
                                            <div class="col-md-12 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Select Membership Plans</label>
                                                <div class="row" id="addPlans">
                                                    <div class="col-sm-6 col-md-6">
                                                        <select required class="form-control " name="plans[]" id="plans">
                                                            <option value="">Select Membership</option>
                                                            @if(!empty(@$memberships and count($memberships)))
                                                                @foreach (@$memberships as $item)
                                                                    <option value="{{$item->id}}">{{$item->plan_name}}</option>
                                                                @endforeach
                                                            @endif
                                                          
                                                        </select> 
                                                    </div>
                                                    <div class="col-sm-4 col-md-4">
                                                    <input required class="form-control input-sm" placeholder="Enter Price" type="number" name="price[]" id="price"> 
                                                    </div>
                                                    <div class="col-sm-2 col-md-2">
                                                        <button onclick="addPlans()" type="button" class="btn btn-success btn-sm">+</button>
                                                        </div>
                                                </div><br>
                                                
                                                
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
                            <h6>Property List</h6>
                        </div>
                        <div class="card-body pt-0 pb-0">
                            <div class="drag-drop-wrap">
                                <div class="table-responsive table-revenue w-100 mb-30">
                                    <table class="table mb-0 table-basic" id="property_details_datatable" width="100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Property Name</th>
                                                <th>Address</th>
                                                <th>Area</th>
                                                <th>Open Time</th>
                                                <th>Close Time</th>
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

