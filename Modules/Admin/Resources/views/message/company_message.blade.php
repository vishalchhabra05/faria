@extends('admin::layouts.master')
@section('admin::custom_css')
<script src="https://www.gstatic.com/firebasejs/6.5.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.5.0/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.9.1/firebase-storage.js"></script>
<style>
.sent-block{
  text-align: end;
}
img {
  border-radius: 50%;
}
</style>
@endsection
@section('admin::content')


<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
            <h1 class="h3 m-0">Service Provider</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/admin/message/message',$id)}}">Message</a></li>
                        <li class="breadcrumb-item active" aria-current="page">All Message</li>
                    </ol>
                </nav>
            </div>
           
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12 mb-4">
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content">
                <div class="without-banner-page">
                    <div class="container">
                    <div class="row">
        <div class="col-sm-12 page-title">
          <h1 class="h1">Message</h1>
        </div>
        <div class="col-sm-12">
          <div class="message-box student">
            <div class="chat-user-info">
              <div class="user-img">
                @if($userData->image)
                  <img src="{{ $userData->image }}" alt="User" hight="100px" width="100px"/>
                @else
                  <img src="{{ asset('/images/default-avatar.jpg') }}" alt="User"/>
                @endif
                <span class="{{ $userData->last_active }}"></span>
              </div>
              <div class="user-details">
                <h4>{{$userData->name}}</h4>
               
               
            </div>
              <div class="messanger-message" style="width: 100%">
                <div class="chat-block custom-scroll" id="userMessage"></div>
              <div class="message-typing">
                <textarea id="message" placeholder="Type..." class="form-control"></textarea>
                
                <button class="send-btn" id="send"><i class="fal fa-paper-plane"></i></button>
                <div id="container"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
<?php $id = auth()->user()->id; ?>

@endsection
@section('admin::custom_js')
<script src="{{asset('company/js/moment.min.js')}}"></script>
<!-- <link rel="stylesheet" href="{{asset('js/emojionearea.min.css')}}"/>
<script type="text/javascript" src="{{asset('js/emojionearea.min.js')}}"></script> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.css" integrity="sha512-0Nyh7Nf4sn+T48aTb6VFkhJe0FzzcOlqqZMahy/rhZ8Ii5Q9ZXG/1CbunUuEbfgxqsQfWXjnErKZosDSHVKQhQ==" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.js" integrity="sha512-hkvXFLlESjeYENO4CNi69z3A1puvONQV5Uh+G4TUDayZxSLyic5Kba9hhuiNLbHqdnKNMk2PxXKm0v7KDnWkYA==" crossorigin="anonymous"></script>


<script type="text/javascript">
{/* For emoji */}
$(document).ready(function() {
    {/* For emoji */}
    $("#message").emojioneArea({
      events: {
        keyup: function(editor, event) {
          $("#message").val(this.getText());
          if (event.which == 13) {
            if(this.getText().trim() != ""){
              $('#send').click();
            }
          }
        }
      }
    });
  });

  var firebaseConfig = {
    apiKey: "AIzaSyBLDwE5qphSoTomCsnwwqMGoryGgtYr2Zo",
    authDomain: "faria-1efd9.firebaseapp.com",
    databaseURL: "https://faria-1efd9.firebaseio.com",
    projectId: "faria-1efd9",
    storageBucket: "faria-1efd9.appspot.com",
    messagingSenderId: "675967142615",
    appId: "1:675967142615:web:439a695745bba40c92be92",
    measurementId: "G-7EDFCR970E"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);

  
  var db = firebase.firestore();
  db.collection("message").doc("{{$id}}").collection("chat").doc('{{$userData->id}}').collection("thread").onSnapshot(function(querySnapshot) {
  querySnapshot.forEach(function(doc) {
    if(doc.id){
        if(doc.data().image){
            var profilePic = doc.data().image;
        }else{
            var profilePic = '{{ $userData->image }}';
        }
        var id = <?php echo $id; ?>;
      
            // check message type
            console.log(doc.data().message_status);
        if(doc.data().type=='send'){
            var className = "sent-block";
        }else{
            var className = "recived-block";
        }

        // display message data
        var messageData = "";
        messageData += '<div class="'+className+'">';
        messageData += '<div class="sent-img"> <img src="'+profilePic+'" alt="img" height="50" width="50"/> <span class="sent-time">'+moment(doc.data().created_at).calendar()+'</span> </div>';
        messageData += '<div class="sent-message">';
        messageData += '<p>'+doc.data().msg+'</p>';
        messageData += '<p>'+doc.data().name+'</p>';
        messageData += '</div>';
        messageData += '</div>';
        $('#userMessage').append(messageData);
        {/*Scroll*/}
        autoScroll();
        db.collection("message").doc("{{$id}}").collection("chat").doc('{{$userData->id}}').update({
            last_seen:true
        });
    }
  });
});

{/* append data in the message and store sent message */}

    $('#send').click(function(){
      var message = $('#message').val();
      $('.emojionearea-editor').empty();
      var timestamp = Date.now();
      {/* check empty message or not */}
      if(message){

          // store dummy data for it is compulsory
          db.collection("message").doc("{{$id}}").collection("chat").doc("{{$userData->id}}").set({
            last_msg:timestamp,
            last_msgText:message,
            last_seen:false
          });
          // store data for sender
          db.collection("message").doc("{{$id}}").collection("chat").doc('{{$userData->id}}').collection("thread").doc(""+timestamp).set({
            // created_at: timestamp,
            // image: '{{auth()->user()->image}}',
            // message: message,
            // messageType:"text",
            // name: '{{auth()->user()->first_name}}',
            // type:"send"

            senderId: '{{$id}}',
            timestamp: timestamp,
            msg : message,
            image : '{{auth()->user()->image}}',
            name : '{{auth()->user()->first_name}}',
            reciever_id : '{{$userData->id}}',
            message_type : 'message',
            message_status : true,
            type:"send"
          }).then(function() {
            console.log('message send success')
            {/*  // after success  */}

          }).catch(function(error) {
            console.error("Error send writing document: ", error);
          });
          // store dummy data for fetch chat data it is compulsory
          db.collection("message").doc("{{$userData->id}}").collection("chat").doc('{{$id}}').set({
            last_msg:timestamp,
            last_msgText:message,
            last_seen:false
          });
          // store data for receiver
          // store data for receiver
          db.collection("message").doc("{{$userData->id}}").collection("chat").doc('{{$id}}').collection("thread").doc(""+timestamp).set({
            // created_at: timestamp,
            // image: '{{auth()->user()->image}}',
            // message: message,
            // messageType:"text",
            // name: '{{$userData->first_name}}',
            // type:"receive"

            senderId: '{{$userData->id}}',
            timestamp: timestamp,
            msg : message,
            image : '{{auth()->user()->image}}',
            name : '{{auth()->user()->first_name}}',
            reciever_id : '{{$id}}',
            message_type : 'message',
            message_status : true,
            type:"recive"
          }).then(function() {
            console.log("Document receive  successfully written!");
          }).catch(function(error) {
            console.error("Error receive writing document: ", error);
          });
          $('#message').empty();
          $('#userMessage').empty();
          {/*Scroll*/}
          autoScroll();

       }
    })
  

  function autoScroll(){
    $('#userMessage').scrollTop($('#userMessage')[0].scrollHeight);
  }
</script>
@endsection



