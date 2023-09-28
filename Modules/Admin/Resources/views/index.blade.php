@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
    <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Dashboard</h1>
            </div> 
        </div>
    </div>
    
    <div class="col-sm-12">
        <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content">
                            <h6>Total Customers</h6>
                            <p class="h1 m-0">{{$app ?? '1' }}</p>
                            <input type="hidden" value="commit_virendra27-01-2022:5:32">
                        </div>
                        <!-- <div class="box-icon chart">
                            <div id="product-sold" style="width: 100%; height: 100px;"><div style="position: relative;"><div dir="ltr" style="position: relative; width: 100px; height: 100px;"><div aria-label="A chart." style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;"><svg width="100" height="100" aria-label="A chart." style="overflow: hidden;"><defs id="_ABSTRACT_RENDERER_ID_2"><clipPath id="_ABSTRACT_RENDERER_ID_3"><rect x="19" y="19" width="62" height="62"></rect></clipPath></defs><rect x="0" y="0" width="100" height="100" stroke="none" stroke-width="0" fill="#ffffff"></rect><g><rect x="29" y="87" width="42" height="7" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><rect x="29" y="87" width="42" height="7" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="46" y="92.95" font-family="Arial" font-size="7" stroke="none" stroke-width="0" fill="#222222">revenue</text></g><path d="M29,90.5L43,90.5" stroke="#3366cc" stroke-width="2" fill-opacity="1" fill="none"></path></g></g><g><rect x="19" y="19" width="62" height="62" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g clip-path="url(http://localhost/admin-dashboard/dashboard.php?#_ABSTRACT_RENDERER_ID_3)"><g><rect x="19" y="80" width="62" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="19" y="19" width="62" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect></g><g><rect x="19" y="80" width="62" height="1" stroke="none" stroke-width="0" fill="#333333"></rect></g><g><path d="M27.125,47.11775C27.125,47.11775,37.29166666666667,61.719625,42.375,62.2C47.45833333333333,62.680375,52.54166666666667,48.78,57.625,50C62.708333333333336,51.22,72.875,69.52,72.875,69.52" stroke="#3366cc" stroke-width="2" fill-opacity="1" fill="none"></path></g></g><g></g><g><g><text text-anchor="end" x="17" y="82.95" font-family="Arial" font-size="7" stroke="none" stroke-width="0" fill="#444444">0</text></g><g><text text-anchor="end" x="17" y="21.95" font-family="Arial" font-size="7" stroke="none" stroke-width="0" fill="#444444">4,…</text><rect x="2" y="16" width="15" height="7" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect></g></g></g><g></g></svg><div aria-label="A tabular representation of the data in the chart." style="position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;"><table><thead><tr><th>option</th><th>revenue</th></tr></thead><tbody><tr><td>Revenue</td><td>2,189</td></tr><tr><td>Product Sold</td><td>1,200</td></tr><tr><td>New Customer</td><td>2,000</td></tr><tr><td>New Visitors</td><td>720</td></tr></tbody></table></div></div></div><div aria-hidden="true" style="display: none; position: absolute; top: 110px; left: 110px; white-space: nowrap; font-family: Arial; font-size: 7px;">revenue</div><div></div></div></div>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content">
                            <h6>Total Service Professionals</h6>
                            <p class="h1 m-0">{{$service ?? '2'}}</p>
                        </div>
                        <!-- <div class="box-icon chart">
                            <div id="product-sold" style="width: 100%; height: 100px;"><div style="position: relative;"><div dir="ltr" style="position: relative; width: 100px; height: 100px;"><div aria-label="A chart." style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;"><svg width="100" height="100" aria-label="A chart." style="overflow: hidden;"><defs id="_ABSTRACT_RENDERER_ID_2"><clipPath id="_ABSTRACT_RENDERER_ID_3"><rect x="19" y="19" width="62" height="62"></rect></clipPath></defs><rect x="0" y="0" width="100" height="100" stroke="none" stroke-width="0" fill="#ffffff"></rect><g><rect x="29" y="87" width="42" height="7" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><rect x="29" y="87" width="42" height="7" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="46" y="92.95" font-family="Arial" font-size="7" stroke="none" stroke-width="0" fill="#222222">revenue</text></g><path d="M29,90.5L43,90.5" stroke="#3366cc" stroke-width="2" fill-opacity="1" fill="none"></path></g></g><g><rect x="19" y="19" width="62" height="62" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g clip-path="url(http://localhost/admin-dashboard/dashboard.php?#_ABSTRACT_RENDERER_ID_3)"><g><rect x="19" y="80" width="62" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="19" y="19" width="62" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect></g><g><rect x="19" y="80" width="62" height="1" stroke="none" stroke-width="0" fill="#333333"></rect></g><g><path d="M27.125,47.11775C27.125,47.11775,37.29166666666667,61.719625,42.375,62.2C47.45833333333333,62.680375,52.54166666666667,48.78,57.625,50C62.708333333333336,51.22,72.875,69.52,72.875,69.52" stroke="#3366cc" stroke-width="2" fill-opacity="1" fill="none"></path></g></g><g></g><g><g><text text-anchor="end" x="17" y="82.95" font-family="Arial" font-size="7" stroke="none" stroke-width="0" fill="#444444">0</text></g><g><text text-anchor="end" x="17" y="21.95" font-family="Arial" font-size="7" stroke="none" stroke-width="0" fill="#444444">4,…</text><rect x="2" y="16" width="15" height="7" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect></g></g></g><g></g></svg><div aria-label="A tabular representation of the data in the chart." style="position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;"><table><thead><tr><th>option</th><th>revenue</th></tr></thead><tbody><tr><td>Revenue</td><td>2,189</td></tr><tr><td>Product Sold</td><td>1,200</td></tr><tr><td>New Customer</td><td>2,000</td></tr><tr><td>New Visitors</td><td>720</td></tr></tbody></table></div></div></div><div aria-hidden="true" style="display: none; position: absolute; top: 110px; left: 110px; white-space: nowrap; font-family: Arial; font-size: 7px;">revenue</div><div></div></div></div>
                        </div> -->
                    </div>
                </div>
            </div>
            <!-- <div class="col-xl-3 col-md-6 mb-4">
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content">
                            <h6>Total Payment Received</h6>
                            <p class="h1 m-0">77</p>
                        </div>
                         <div class="box-icon chart">
                            <div id="product-sold" style="width: 100%; height: 100px;"><div style="position: relative;"><div dir="ltr" style="position: relative; width: 100px; height: 100px;"><div aria-label="A chart." style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;"><svg width="100" height="100" aria-label="A chart." style="overflow: hidden;"><defs id="_ABSTRACT_RENDERER_ID_2"><clipPath id="_ABSTRACT_RENDERER_ID_3"><rect x="19" y="19" width="62" height="62"></rect></clipPath></defs><rect x="0" y="0" width="100" height="100" stroke="none" stroke-width="0" fill="#ffffff"></rect><g><rect x="29" y="87" width="42" height="7" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><rect x="29" y="87" width="42" height="7" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="46" y="92.95" font-family="Arial" font-size="7" stroke="none" stroke-width="0" fill="#222222">revenue</text></g><path d="M29,90.5L43,90.5" stroke="#3366cc" stroke-width="2" fill-opacity="1" fill="none"></path></g></g><g><rect x="19" y="19" width="62" height="62" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g clip-path="url(http://localhost/admin-dashboard/dashboard.php?#_ABSTRACT_RENDERER_ID_3)"><g><rect x="19" y="80" width="62" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="19" y="19" width="62" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect></g><g><rect x="19" y="80" width="62" height="1" stroke="none" stroke-width="0" fill="#333333"></rect></g><g><path d="M27.125,47.11775C27.125,47.11775,37.29166666666667,61.719625,42.375,62.2C47.45833333333333,62.680375,52.54166666666667,48.78,57.625,50C62.708333333333336,51.22,72.875,69.52,72.875,69.52" stroke="#3366cc" stroke-width="2" fill-opacity="1" fill="none"></path></g></g><g></g><g><g><text text-anchor="end" x="17" y="82.95" font-family="Arial" font-size="7" stroke="none" stroke-width="0" fill="#444444">0</text></g><g><text text-anchor="end" x="17" y="21.95" font-family="Arial" font-size="7" stroke="none" stroke-width="0" fill="#444444">4,…</text><rect x="2" y="16" width="15" height="7" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect></g></g></g><g></g></svg><div aria-label="A tabular representation of the data in the chart." style="position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;"><table><thead><tr><th>option</th><th>revenue</th></tr></thead><tbody><tr><td>Revenue</td><td>2,189</td></tr><tr><td>Product Sold</td><td>1,200</td></tr><tr><td>New Customer</td><td>2,000</td></tr><tr><td>New Visitors</td><td>720</td></tr></tbody></table></div></div></div><div aria-hidden="true" style="display: none; position: absolute; top: 110px; left: 110px; white-space: nowrap; font-family: Arial; font-size: 7px;">revenue</div><div></div></div></div>
                        </div> 
                    </div>
                </div>
            </div>
           

            

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content">
                            <h6>Total Booking</h6>
                            <p class="h1 m-0">000</p>
                        </div>
                        <div class="box-icon chart">
                            <div id="product-sold" style="width: 100%; height: 100px;"><div style="position: relative;"><div dir="ltr" style="position: relative; width: 100px; height: 100px;"><div aria-label="A chart." style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;"><svg width="100" height="100" aria-label="A chart." style="overflow: hidden;"><defs id="_ABSTRACT_RENDERER_ID_2"><clipPath id="_ABSTRACT_RENDERER_ID_3"><rect x="19" y="19" width="62" height="62"></rect></clipPath></defs><rect x="0" y="0" width="100" height="100" stroke="none" stroke-width="0" fill="#ffffff"></rect><g><rect x="29" y="87" width="42" height="7" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><rect x="29" y="87" width="42" height="7" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="46" y="92.95" font-family="Arial" font-size="7" stroke="none" stroke-width="0" fill="#222222">revenue</text></g><path d="M29,90.5L43,90.5" stroke="#3366cc" stroke-width="2" fill-opacity="1" fill="none"></path></g></g><g><rect x="19" y="19" width="62" height="62" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g clip-path="url(http://localhost/admin-dashboard/dashboard.php?#_ABSTRACT_RENDERER_ID_3)"><g><rect x="19" y="80" width="62" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="19" y="19" width="62" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect></g><g><rect x="19" y="80" width="62" height="1" stroke="none" stroke-width="0" fill="#333333"></rect></g><g><path d="M27.125,47.11775C27.125,47.11775,37.29166666666667,61.719625,42.375,62.2C47.45833333333333,62.680375,52.54166666666667,48.78,57.625,50C62.708333333333336,51.22,72.875,69.52,72.875,69.52" stroke="#3366cc" stroke-width="2" fill-opacity="1" fill="none"></path></g></g><g></g><g><g><text text-anchor="end" x="17" y="82.95" font-family="Arial" font-size="7" stroke="none" stroke-width="0" fill="#444444">0</text></g><g><text text-anchor="end" x="17" y="21.95" font-family="Arial" font-size="7" stroke="none" stroke-width="0" fill="#444444">4,…</text><rect x="2" y="16" width="15" height="7" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect></g></g></g><g></g></svg><div aria-label="A tabular representation of the data in the chart." style="position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;"><table><thead><tr><th>option</th><th>revenue</th></tr></thead><tbody><tr><td>Revenue</td><td>2,189</td></tr><tr><td>Product Sold</td><td>1,200</td></tr><tr><td>New Customer</td><td>2,000</td></tr><tr><td>New Visitors</td><td>720</td></tr></tbody></table></div></div></div><div aria-hidden="true" style="display: none; position: absolute; top: 110px; left: 110px; white-space: nowrap; font-family: Arial; font-size: 7px;">revenue</div><div></div></div></div>
                        </div>
                    </div>
                </div>
            </div> -->

        </div>
       
    </div>
   

          
       
    </div>
   
</div>    
    
@endsection
@section('admin::custom_js')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
  
</script>
@endsection