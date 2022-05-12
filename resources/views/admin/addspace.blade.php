<div class="contents" id="top">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Add Space</h4>
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
                            <h6>Add Space</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <form id="frm_add_space">
                                        <input type="hidden" name="_token" value="">       
                                               <input type="hidden" name="id" id="id">
                                        <div class="form-row mx-n15">
                                            <div class="col-md-6 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Space Name</label>
                                                <input type="text" class="form-control ih-medium ip-light radius-xs b-light" placeholder="Enter Space Name" name="space_name" id="space_name">
                                            </div>
                                            <div class="col-md-6 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Space Type</label>
                                               
                                               <select class="form-control" name="space_type" id="space_type">
                                                   <option value="">Select Space Type</option>
                                                   @if(!empty(@$space_types) and count(@$space_types))
                                                        @foreach($space_types as $space_type)
                                                        <option value="{{$space_type->id}}">{{$space_type->name}}</option>
                                                        @endforeach
                                                   @endif 
                                               </select>
                                            </div>
                                            <div class="col-md-6 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Address</label>
                                                <textarea type="text" class="form-control ih-medium ip-light radius-xs b-light" placeholder="Enter Space Address" name="address" id="address"></textarea>
                                            </div>  
                                            <div class="col-md-6 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">SELECT City</label>
                                               
                                               <select class="form-control" name="city" id="city">
                                                   <option value="">Select City</option>
                                                   @if(!empty(@$cities) and count(@$cities))
                                                        @foreach($cities as $city)
                                                        <option value="{{$city->id}}">{{$city->location}}</option>
                                                        @endforeach
                                                   @endif 
                                               </select>
                                            </div> 
                                            <div class="col-md-6 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Seating Capacity</label>
                                                <input type="number" class="form-control ih-medium ip-light radius-xs b-light" placeholder="Enter Seating Capacity" name="seat_capacity" id="seat_capacity">
                                            </div>
                                            <div class="col-md-6 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Area Type</label>
                                                <input type="text" class="form-control ih-medium ip-light radius-xs b-light" placeholder="Enter Area Type" name="area_type" id="area_type">
                                            </div>
                                            <div class="col-md-6 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Contact details</label>
                                                <input type="email" class="form-control ih-medium ip-light radius-xs b-light" placeholder="Enter Email" name="email" id="email">
                                            </div>
                                            <div class="col-md-6 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Mobile No</label>
                                                <input type="number" class="form-control ih-medium ip-light radius-xs b-light" placeholder="Enter Mobile No" name="mobile" id="mobile">
                                            </div>
                                            <div class="form-row mx-n15">
                                                <div class="col-md-12 mb-20 px-15">
                                                    <label for="validationDefault02" class="il-gray fs-14 fw-500 align-center">Image</label>
                                                    <a style="width: 100%;" href="javascript:void(0)" class="btn btn-lg btn-outline-lighten btn-upload" onclick="$('#image').click()"> <span data-feather="upload"></span> Click to Upload</a>
                                                    <input type="file" required="" class="form-control  ih-medium ip-light radius-xs b-light" name="image[]" id="image" accept="image/*" style="opacity: 0;position: absolute;" onchange="showSelectedImage(this)" multiple="multiple">
                                                </div>
                                            </div>
                                        </div> 
                                        <button class="btn btn-xs btn-primary px-30 float-right mt-3" type="submit" id="button">Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ends: .card -->
                </div>
                <div class="col-12 ">
                <div class="card mb-25">
                    <div class="card-header">
                        <h6>Space Type List</h6>
                    </div>
                    <div class="card-body pt-0 pb-0">
                        <div class="drag-drop-wrap">
                            <div class="table-responsive table-revenue w-100 mb-30">
                                <table class="table mb-0 table-basic" id="add_spacetype_datatable" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Space</th>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>Seat Capacity</th>
                                            <th>Area</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
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

