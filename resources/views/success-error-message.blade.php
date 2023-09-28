@if(Session::has('message')!= null)
   <div class="alert alert-success">{{ Session::get('message') }}<span>X</span></div>
@endif
@if(Session::has('success')!= null)
   <div class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success') }}<span>X</span></div>
@endif
@if(Session::has('error')!= null)
   <div class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('error') }}<span>X</span></div>
@endif
@if(!isset($errors))
<div class="alert alert-danger"><?php foreach($errors->all() as $error){ echo $error."<br>"; } ?><span>X</span></div>
@endif
