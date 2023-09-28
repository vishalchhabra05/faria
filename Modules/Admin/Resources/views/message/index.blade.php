@extends('admin::layouts.master')
@section('admin::custom_css')
<script src="https://www.gstatic.com/firebasejs/6.5.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.5.0/firebase-firestore.js"></script>
<style>
img {
  border-radius: 50%;
}
dl, ol, ul {
    margin-top: 0;
    list-style-type: none;
    margin-bottom: 1rem;
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
                        <li class="breadcrumb-item active" aria-current="page">Message</li>
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
                        <h1 class="h1">Messages</h1>
                        </div>
                        <div class="col-sm-12">
                        <div class="message-box student">
                            <div class="messanger-list" style="width:100%">
                                <form class="message-search">
                                    <input type="text" placeholder="Search" class="form-control" id="SearchStudent" onkeyup="searchStudent(this)">
                                    <!-- <button class="search-btn"><i class="fal fa-search"></i></button> -->
                                </form>
                            <ul class="custom-scroll" id="profileList">
                            <li id="not" style="background-color: lightyellow;font-weight: bolder;">Not Found</li></ul>
                            </div>

                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  

@endsection
@section('admin::custom_js')

<script type="text/javascript">
// $(document).ready(function(){
//   console.log();  
// })
   // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
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


//   $(document).ready(function(){

//     var message = "Testing only";
//   var timestamp = Date.now();
//   {/* check empty message or not */}
//   if(message){

//       // store dummy data for it is compulsory
//       db.collection("message").doc("8").collection("chat").doc("21").set({
//         last_msg:timestamp,
//         last_msgText:message,
//         last_seen:false
//       });
//       // store data for sender
//       db.collection("message").doc("8").collection("chat").doc('21').collection("thread").doc(""+timestamp).set({
//         created_at: timestamp,
//         image: '{{auth()->user()->image}}',
//         message: message,
//         messageType:"text",
//         name: '{{auth()->user()->first_name}}',
//         type:"send"
//       }).then(function() {
//         {/*  // after success  */}

//       }).catch(function(error) {
//         console.error("Error send writing document: ", error);
//       });
//       // store dummy data for fetch chat data it is compulsory
//       db.collection("message").doc("21").collection("chat").doc('8').set({
//         last_msg:timestamp,
//         last_msgText:message,
//         last_seen:false
//       });
//       // store data for receiver
//       // store data for receiver
//       db.collection("message").doc("21").collection("chat").doc('8').collection("thread").doc(""+timestamp).set({
//         created_at: timestamp,
//         image: '{{auth()->user()->image}}',
//         message: message,
//         messageType:"text",
//         name: 'Mohan',
//         type:"receive"
//       }).then(function() {
//         console.log("Document receive  successfully written!");
//       }).catch(function(error) {
//         console.error("Error receive writing document: ", error);
//       });
//    }
// });



// $(document).ready(function(){
// var message = "Testing only";
// var timestamp = Date.now();
// {/* check empty message or not */}
// if(message){

//   // store dummy data for it is compulsory
//   db.collection("message").doc("8").collection("chat").doc("21").set({
//     last_msg:timestamp,
//     last_msgText:message,
//     last_seen:false
//   });
//   // store data for sender
//   db.collection("message").doc("8").collection("chat").doc('21').collection("thread").doc(""+timestamp).set({
//     // created_at: timestamp,
//     // image: '{{auth()->user()->image}}',
//     // message: message,
//     // messageType:"text",
//     // name: '{{auth()->user()->first_name}}',


//     senderId: '8',
//     timestamp: timestamp,
//     msg : message,
//     image : '{{auth()->user()->image}}',
//     name : 'Mohan',
//     reciever_id : '21',
//     message_type : 'message',
//     message_status : true,
//      type:"receive"


//   }).then(function() {
//     {/*  // after success  */}

//   }).catch(function(error) {
//     console.error("Error send writing document: ", error);
//   });
//   // store dummy data for fetch chat data it is compulsory
//   db.collection("message").doc("21").collection("chat").doc('8').set({
//     last_msg:timestamp,
//     last_msgText:message,
//     last_seen:false
//   });
//   // store data for receiver
//   // store data for receiver
//   db.collection("message").doc("21").collection("chat").doc('8').collection("thread").doc(""+timestamp).set({
//     // created_at: timestamp,
//     // image: '{{auth()->user()->image}}',
//     // message: message,
//     // messageType:"text",
//     // name: 'Mohan',
//     // type:"receive"

//     senderId: '21',
//     timestamp: timestamp,
//     msg : message,
//     image : '{{auth()->user()->image}}',
//     name : 'raj',
//     reciever_id : '8',
//     message_type : 'message',
//     message_status : true,
//       type:"receive"


   
//   }).then(function() {
//     console.log("Document receive  successfully written!");
//   }).catch(function(error) {
//     console.error("Error receive writing document: ", error);
//   });
// }
// });

  db.collection("message").doc("{{$id}}").collection("chat").onSnapshot(function(querySnapshot) {
        querySnapshot.forEach(function(doc) {
            getTotalUserList();
        })
    })


  {/* Get side bar user list */}
  function getTotalUserList(){
    $("#not").hide();
    $("#profileList").html('');
    var userMessage = [];
    var userList = [];
    
      db.collection("message").doc("{{$id}}").collection("chat").get()
        .then(function(querySnapshot) {
          
          querySnapshot.forEach(function(doc){
            userList.push({
              id:doc.id,
              last_msg:doc.data().last_msg,
              last_msgText:doc.data().last_msgText,
              last_seen:doc.data().last_seen,
          });
          {/* make a array of user Id */}
        });
       console.log(userList);
        if(userList.length > 0){
            $.ajax({
                type:'POST',
                url:"{{url('/admin/message-user-data')}}",
                data:{"userData":userList,"_token":"{{ csrf_token() }}"},
                success:function(data){
                    var list = "";
                    var count = 1;
                    data.forEach(index => {
                        var url = '{{url("admin/message-detail")}}';
                        url = url+'/'+index.slug;
                        url = url+'/'+{{$id}}
                        if(index.userProfile){
                             img = index.userProfile;
                        }else{
                             img = '{{asset("assets/images/default-avatar.png")}}';
                        }
                        
                        if(index.last_seen == 'true'){
                            list +='<li id="displayMessage-'+index.id+'">';
                        }else{
                            
                            list +='<li id="displayMessage-'+index.id+'" style="background-color: lightyellow;font-weight: bolder;">';
                        }
                        list +='<a id="show-'+count+'" href="'+url+'" >';
                        list +='<div class="messenger-img"><img src="'+img+'" alt="img" height="50" width="50"/><span class="'+index.active+'"></span></div>';
                        list +='<div class="messanger-user-details">';
                        list +='<h4>'+index.name+'</h4>';
                        list +='<p style="white-space: nowrap;width: 225px;overflow: hidden;text-overflow: ellipsis;">'+index.last_msgText+'</p>';
                        list +='<div class="last-message-time">'+index.ago+'</div>';
                        list +='</div>';
                        list +='</a>';
                        list +='</li>';
                        $("#profileList").html(list);
                        count++;
                    });
                   
                }
            });
        }
        

        }).catch(function(error){
          console.log("Error getting documents: ", error);
      });
  }


// function getTotalUserList(){
//     $("#profileList").html('');
//     var userMessage = [];
//     var userList = [];
//       db.collection("message").doc("{{$id}}").collection("chat").get()
//         .then(function(querySnapshot) {
//           querySnapshot.forEach(function(doc){
//              // console.log(doc.data().last_msg);
//             userList.push({
//               id:doc.id,
//               last_msg:doc.data().last_msg,
//               last_msgText:doc.data().last_msgText,
//               last_seen:doc.data().last_seen,
//           });
//           {/* make a array of user Id */}
//         });
        
//         if(userList.length > 0){
//             $.ajax({
//                 method:'POST',
//                 url:"{{url('/admin/message-user-data')}}",
//                 data:{"userData":userList,"_token":"{{ csrf_token() }}"},
//                 success:function(data){
//                     var list = "";
//                     var count = 1;
                    
//                     data.forEach(index => {
//                         var messageCount = 0;
//                         var messageBages = '';
//                         db.collection("message").doc("{{$id}}").collection("chat").doc(""+index.id).collection("thread").where("last_seen",'==',false).onSnapshot(function(Snapshot1) {
                         
//                             // Snapshot1.forEach(function(doc1) {
//                             //     if(doc1.data().last_seen == 'false'){
//                             //         messageCount++;   
//                             //     }
//                             // })
                           
                          
//                             //     console.log(messageCount)
//                             if(messageCount != 0){
//                                 messageBages = '<span style="position: absolute;bottom: 5px;right: 10px;padding: 0;border-radius: 100%;background-color:green;color: white;width: 20px; height: 20px; line-height: 20px; text-align: center; font-size: 13px;" >'+messageCount+'</span>';
//                             } 
                        
//                             var url = '{{url("admin/message-detail")}}';
//                             url = url+'/'+index.slug;
//                             url = url+'/'+{{$id}}
//                             if(index.userProfile){
//                                 var userProfile = index.userProfile;
//                             }else{
//                                 var userProfile = '{{asset("assets/images/default-avatar.png")}}';
//                             }
//                             if(index.last_seen == 'true'){
//                                 list +='<li id="displayMessage-'+index.id+'"><a href="'+url+'">';
//                             }else{
//                                 list +='<li id="displayMessage-'+index.id+'" style="background-color: lightyellow;font-weight: bolder;"><a href="'+url+'">';
//                             }
//                             list +='<div class="user-img"> <div id="show-'+count+'" class="img"><img src="'+userProfile+'" alt="user" width="50" height="50"></div></div><div class="user-details"> <div id="show-'+count+'" class="user-name">'+index.name+'</div> <span class="last-message"><i class="zmdi zmdi-mail-reply"></i> '+index.last_msgText+'</span><span class="last-message-date">'+index.ago+'</span> </div>';
//                             list += messageBages;
//                             list +='</a></li>';
                           
//                             $("#profileList").html(list);
//                         });
//                             count++;
                        
//                      });
//                 }
//             });
//         }

//         }).catch(function(error){
//           console.log("Error getting documents: ", error);
//       });
//   }

//search tutor message
function searchStudent(event){
    var filter, ul, li, a, i, txtValue;
    filter = event.value.toUpperCase();
    ul = document.getElementById("profileList");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("a")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}
 </script>
@endsection 