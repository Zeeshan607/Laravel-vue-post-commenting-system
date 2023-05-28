
<style>

.commenter-image{
    width:70px;
    height: 70px;
}
.comment-text{
    position:relative;
    overflow: hidden;

}
.comment-actions{
    text-decoration:none;
    font-size:12px;
}


.pretty.p-icon{
    margin-right:40px;
    width:45px;
}
.pretty.p-icon label{
    position:absolute;
    /*right:-30px;*/
    right:-2px;
}

@media only screen and (max-width:500px) and (min-width:0px){
    .comment-text{
        padding:2%!important;
    }
    .comment-text p{
        display:flex;
        flex-wrap: wrap;
        font-family: "titilium"!important;
    }
    .replies-step1-row .col-12 .commenter-image{
        width:40px;
        height: 40px;
    }
    .replies-step1-row .col-12{
        padding-left:50px;
        padding-right:0px!important;
        font-family: "titilium"!important;
    }
    .replies-step1-row .col-12 p{
    font-size:16px;
        font-family: "titilium"!important;
    }
    .replies-step1-row .col-12 h5{
        font-size:16px;
    }
    .replies-step2-row .col-12{
        padding:0px 0px 0px 30px!important;
        font-family: "titilium"!important;
    }
    .replies-step2-row .col-12 p{
        font-size:15px;
        /*font-weight: bold;*/
        font-family: "titilium"!important;
    }
    .replies-step2-row .col-12 h5{
        font-size:15px;

    }
    .replies-step2-row .col-12 .commenter-image{
        width:35px!important;
        height: 35px!important;

    }
}
@media only screen and  (min-width:1200px){
    .replies-step1-row .col-12{
        padding-left:12%;

    }
}
@media only screen and (max-width:768px) and (min-width:501px){
    .replies-step1-row .col-12{
        padding-left:12%;

    }
    .replies-step1-row .col-12 .commenter-image{
        width:40px;
        height: 40px;
    }
    .replies-step2-row .col-12 .commenter-image{
        width:35px!important;
        height: 35px!important;

    }
}
@media only screen and (max-width:1200px) and (min-width:769px){
    .replies-step1-row .col-12{
        padding-left:12%;

    }
}
</style>


<template>
    <div class="container-xl mt-5">
        <div class="row mx-0">
            <div class="col-12">
                <h1>Comments:</h1>
            </div>
        </div>


            <div class="row mx-0 mt-3">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group comment-thread" id="comment-group">
                        <textarea name="comment-thread" rows="5" cols="10" @keyup.enter="sendComment($event)" placeholder="Write your comment here.."  id="comment-text" class="form-control stop-enter-space"></textarea>
                       <div v-if="validate && validate.comment" >
                            <div class="alert alert-warning mt-3  alert-dismissible fade show" role="alert">{{validate.comment }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                       </div>
                        <div class="row mx-0 mt-2">
                            <div class="col-12 text-right p-0">

                                <div class="condition-wrapper" v-if="auth_id.length !== 0">
                                    <button class="btn cb ml-auto" id="submitComment" v-on:click="sendComment($event)">Submit</button>
                                </div>
                                <div v-else>
                                    <a href="javascript:void(0);" onclick="window.location='/login'" class="btn cb ml-auto">Login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


                <div class="row mx-0">
                    <div class="col-12 col-sm-12 col-lg-8 col-md-8">
                        <div v-if="requestMessage">
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                {{requestMessage}}
                                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


        <div class="row mx-0 mt-5">
            <div class="col-12 col-sm-12 col-md-10 col-lg-10">
                <ul class="list-unstyled">
                    <li  v-for="comment in comments" :key="comment.id" id="parent-comment" class="my-2">
                        <div class="row mx-0 user-comment">
                            <div class="col-2 comment-img p-0 d-flex flex-row align-item-center justify-content-center">

                                <div v-if="comment?.user?.profile_picture">
                                    <img :src="comment?.user?.profile_picture" :alt="comment?.user?.name" class="commenter-image rounded-circle"/>
                                </div>
                                <div v-else>
                                    <img src="/assets/images/user_placeholder.jpg" alt="Commenter-placeholder" class="commenter-image rounded-circle"/>
                                </div>

                            </div>
                            <div class="col-10 p-0 comment-text">
                                <h5 class="m-0"><strong><b>{{comment.user?comment.user.name:"Guest"}}</b></strong></h5>
                                <p class="mb-0">{{comment.comment}}</p>


                                <div class="action-pack">
                                    <!--heart checkbox for likes-->
                                <div class="pretty p-icon p-toggle p-plain ">
                                    <input type="checkbox" id="like_checkbox"
                                     v-bind:checked="(auth_id == findCommentLikeForUser(comment,auth_id)) && (comment.id == findCommentLikeForComment(comment))" v-on:click="likeTheComment($event,comment.id)"/>
                                    <div class="state p-off ">
                                        <i class="icon far fa-heart text-danger"></i>
                                    </div>
                                    <div class="state p-on p-danger-o">
                                        <i class="icon fa fa-heart"></i>
                                    </div>
                                    <label class="comment_like_count">{{comment.likes_count}}</label>
                                </div>
<!--                                -->
                                <a href="javascript:void(0)" class="comment-actions" v-on:click.prevent="showReplyInput($event)">Reply</a>
                                 <a href="javascript:void(0);" class="comment-actions ms-2"  v-if="auth_id && comment.user_id == auth_id" v-on:click="delComment(comment.id,$event)" >Delete</a>


                                <div class=" col-12 col-sm-12 col-md-8 col-lg-8 reply-input" id="0">
                                        <div class="d-none form-group ml-4 mt-3 reply" id="1" >
                                            <textarea name="comment-reply" rows="3" cols="5" @keyup.enter="sendCommentReply($event, comment.id)" placeholder="Write your comment here.."  id="comment-reply-text" class="form-control stop-enter-space"></textarea>
                                               <div v-if="validate && validate.comment_reply" >
                                                   <div class="alert alert-warning mt-3 alert-dismissible fade show" role="alert">
                                                       {{validate.comment_reply}}
                                                       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                   </div>
                                               </div>
                                            <div class="row mx-0 mt-2">
                                                <div class="col-12 text-end p-0">
                                                    <a href="javascript:void(0)" class="comment-actions me-2" v-on:click.prevent="closeReply($event)">Cencel Reply</a>
                                                    <button class="btn cb btn-md ms-auto" id="submitCommentReply" v-on:click="sendCommentReply($event, comment.id)">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                </div>
<!--                                action pack ends-->
                            </div>
                        </div>
                        <div class="row mx-0 replies-step1-row">
                            <div class="col-12 ">
                                <!--comment replies-->
                                <ul class="list-unstyled  replies-step1-ul">
                                    <li v-for="reply in comment.replies" :id="reply.id" class="my-3">
                                        <div class="row mx-0 user-comment">
                                            <div class="col-2 comment-img p-0 d-flex flex-row align-item-center justify-content-center">
                                                <div v-if="reply?.user?.profile_picture">
                                                    <img :src="reply?.user?.profile_picture" :alt="reply?.user?.name" class="commenter-image rounded-circle"/>
                                                </div>
                                                <div v-else>
                                                    <img src="/assets/images/user_placeholder.jpg" alt="Commenter-placeholder" class="commenter-image rounded-circle"/>
                                                </div>
                                            </div>
                                            <div class="col-10 p-0 comment-text">
                                                <h5 class="m-0"><strong><b>{{reply.user?reply.user.name:"Guest"}}</b></strong></h5>
                                                <p class="mb-0">{{reply.comment}}</p>
                                             <div class="action-pack">
                                                <!--heart checkbox for likes-->

                                                <div class="pretty p-icon p-toggle p-plain ">
                                                    <input type="checkbox"
                                                           v-bind:checked="(auth_id == findCommentLikeForUser(reply,auth_id)) && (reply.id == findCommentLikeForComment(reply))" v-on:click="likeTheComment($event,reply.id)"/>
                                                    <div class="state p-off ">
                                                        <i class="icon far fa-heart text-danger"></i>
                                                    </div>
                                                    <div class="state p-on p-danger-o">
                                                        <i class="icon fa fa-heart"></i>
                                                    </div>
                                                    <label class="comment_like_count">{{reply.likes?reply.likes.length:0}}</label>
                                                </div>
                                                <!--                                -->
                                                <a href="javascript:void(0)" class="comment-actions" v-on:click.prevent="showReplyInput($event)">Reply</a>
                                                <a href="javascript:void(0)" class="comment-actions ms-2" v-if="auth_id && reply.user_id == auth_id" v-on:click="delComment(reply.id,$event)" >
                                                   Delete
                                                </a>
                                                <div class=" col-12 col-sm-12 col-md-8 col-lg-8 p-0 reply-input" id="">
                                                    <div class="d-none form-group ml-4 mt-3 reply" id="reply-to-reply" >
                                                        <textarea name="comment-reply" rows="3" cols="5" @keyup.enter="sendCommentReply($event,reply.id)" placeholder="Write your comment here.."  id="reply-to-reply-text" class="form-control stop-enter-space"></textarea>
                                                        <div v-if="validate && validate.reply_of_reply" class="mt-3">
                                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                                {{validate.reply_of_reply}}
                                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                            </div>

                                                        </div>
                                                        <div class="row mx-0 mt-2">
                                                            <div class="col-12 text-end p-0">
                                                                <a href="javascript:void(0)"  class=" comment-actions  me-2" v-on:click.prevent="closeReply($event)">Cencel Reply</a>
                                                                <button class="btn cb btn-md  ms-auto" id="submitReplyToReply" v-on:click="sendCommentReply($event, reply.id)">Submit</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                             </div>
                                                <!--                                actions pack ends  -->



                                            </div>
                                        </div>
<!--                                        -->

                                        <div class="row  mx-0 replies-step2-row" style="margin-left:90px">
                                            <div class="col-12">
                                                <ul class="list-unstyled replies-step2-ul">
                                                    <li v-for="rep in reply.replies" :id="rep.id" class="my-3">
                                                        <div class="row mx-0 user-comment">
                                                            <div class="col-2 comment-img p-0 d-flex flex-row align-item-center justify-content-center">

                                                                <div v-if="rep?.user?.profile_picture">
                                                                    <img :src="rep?.user?.profile_picture" :alt="rep?.user?.name" class="commenter-image rounded-circle"/>
                                                                </div>
                                                                <div v-else>
                                                                    <img src="/assets/images/user_placeholder.jpg" alt="Commenter-placeholder" class="commenter-image rounded-circle"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-10 p-0 comment-text">
                                                                <h5><strong><b>{{rep.user?rep.user.name:"Guest"}}</b></strong></h5>
                                                                <p class="mb-0">{{rep.comment}}</p>
                                                                <!--                                actions   -->
                                                                <!--heart checkbox for likes-->

                                                                <div class="pretty p-icon p-toggle p-plain ">
                                                                    <input type="checkbox"
                                                                           v-bind:checked="(auth_id == findCommentLikeForUser(rep,auth_id)) && (rep.id == findCommentLikeForComment(rep))" v-on:click="likeTheComment($event,rep.id)"/>
                                                                    <div class="state p-off ">
                                                                        <i class="icon far fa-heart text-danger"></i>
                                                                    </div>
                                                                    <div class="state p-on p-danger-o">
                                                                        <i class="icon fa fa-heart"></i>
                                                                    </div>
                                                                    <label class="comment_like_count">{{rep.likes?rep.likes.length:0}}</label>
                                                                </div>
                                                                <!--                                -->
                                                                <a href="javascript:void(0)" class="comment-actions" v-if="auth_id && rep.user_id == auth_id" v-on:click="delComment(rep.id,$event)" >
                                                                  Delete
                                                                </a>


                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                    </li>
                                </ul>
                            </div>
                        </div>




                    </li>
                </ul>
            </div>

        </div>
    </div>
</template>

<script>
import swal from 'sweetalert2';

let token= document.querySelector("meta[name='csrf-token']").getAttribute('content');
    export default {
    name:"Index.vue",
    props:['auth_id','post_slug','post_id'],

        data: function () {
            return {
                user_id: this.auth_id,
                post: this.post_slug,
                comments:'',
                validate:{},
                requestMessage:'',
                test_debug:"",
            }
        },
        mounted: function () {

            axios.get('/getPostComments/?post_slug=' + this.post).then(resp => {
                this.comments=resp.data.comments;
                // console.log(this.comments)
            }, (serverErr) => {
                console.log(serverErr)
            }).catch(err => {
                console.log(err)
            });


            $(".stop-enter-space").keydown(function(e){
            // Enter was pressed without shift key
                if (e.keyCode == 13 && !e.shiftKey)
                {
                    // prevent default behavior
                    e.preventDefault();
                }
            });

        },
        methods: {

            ////
            closeReply:function(e){

                    $(e.target).parent().parent().parent('.reply').addClass('d-none');
                    $(e.target).parent().parent().parent('.reply').find("textarea").addClass('disabled');


            },
            ////
            showReplyInput:function(e){

                $(document).find('#parent-comment .reply').removeClass('d-block');
                $(document).find('#parent-comment .reply').find("textarea").addClass('disabled');

                $(e.target).parent(".action-pack").find(".reply-input .reply").toggleClass('d-none');
                $(e.target).parent(".action-pack").find(".reply-input .reply").find("textarea").toggleClass('disabled');

            },
            //
            sendComment:function(e) {

                let commentByInput=$(e.target).val();
                let commentByBtn=$(e.target).parent().parent().parent().parent('.comment-thread').find("textarea[name='comment-thread']").val();
                let comment;
                if(commentByInput){
                    comment=commentByInput;
                }
                if(commentByBtn){
                    comment=commentByBtn;
                }

                let post_slug = this.post;
                let user_id = this.user_id;
                let post_id=this.post_id
                    $(e.target).addClass('disabled');
                let data= {'_token': token, 'comment': comment,'post_id':post_id, 'post_slug': post_slug, 'user_id': user_id};
              if(!comment=="" && !e.shiftKey){
                  axios.post('/submit_comment',data).then(resp=>{
                      this.comments.splice(0, 0, resp.data.comment)
                      $('#comment-text').val('');
                      $(e.target).removeClass('disabled');
                      this.requestMessage=resp.data.success;

                  },(err)=>{
                      //server error
                      console.log(err)
                      $(e.target).removeClass('disabled');
                      if(err.response.status=== 401) {
                          window.location="/login";
                      }
                      if(err.response.status=== 419) {
                          location.reload();
                          window.location="/login";
                      }
                  }).catch(err=>{
                      //request error
                      swal.fire(
                          "Error?",
                          err.message,
                          "error",
                      ).then(function(){
                          location.reload();
                      })
                      $(e.target).removeClass('disabled');
                      console.log(err);
                  });
              }else{

                  $(e.target).removeClass('disabled')
                  Vue.set(this.validate,"comment","Comment field can't be empty");


              }


            },
            //-------------------
            delComment:function(id,e){
                e.preventDefault();
                let token = document.querySelector("meta[name='csrf-token']").getAttribute('content');
                let data={'_token':token,'comment_id':id};
               swal.fire({
                    title:'Are you sure?',
                    text:'You will not be able to recover this Comment!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#324cdd',
                    confirmButtonText: 'Yes, delete it!',
                    // closeOnConfirm: true
                }).then(resp=>{
                    if(resp.isConfirmed){

                        axios.post('/del_comment',data).then(resp=>{
                            let comments =  this.comments;
                            if(resp.data.comment.parent_id != null){
                                comments=comments.map(com=>{

                                    if(com.replies)
                                    {
                                        if(com.replies.some(re=> re.id === resp.data.comment.id)){

                                            com.replies=com.replies.filter(re => re.id !== resp.data.comment.id)

                                        }else{
                                           com.replies.map(reply=>{

                                               if(reply.replies && reply.replies.some(re=> re.id === resp.data.comment.id) ){

                                                reply.replies=reply.replies.filter(re => re.id !== resp.data.comment.id)

                                               }
                                           })
                                        }

                                    }

                                })
                                Vue.set(this.comments,comments);
                            }else{

                                comments = comments.filter(co => co.id !== resp.data.comment.id);
                                this.comments=comments;
                            }

                            this.requestMessage=resp.data.success;
                        },(servErr)=>{
                            if(err.response.status === 401) {
                                window.location="/login";
                            }
                            if(err.response.status === 419) {
                                location.reload();
                                window.location="/login";
                            }
                        }).catch(err=>{
                            swal.fire(
                                "Error?",
                                err.message,
                                "error",
                            ).then(function(){
                                location.reload();
                            })

                        });
                    }
                });

            },

            //
            sendCommentReply: function(e, parent_id){

                let replyByBtnClick=$(e.target).parent().parent().parent('.reply').find("textarea[name='comment-reply']").val();
                let replyByEnter=$(e.target).val();
                    let reply;
                    if(replyByBtnClick){
                        reply=replyByBtnClick;
                    }
                    if(replyByEnter){
                        reply=replyByEnter;
                    }
                    $(e.target).addClass('disabled');
                    let data={"_token":token,"comment_id":parent_id,"user_id":this.user_id,"post_slug":this.post,"comment":reply};

                if( reply !== "" && !e.shiftKey){
                                axios.post('/submit_comment_reply',data).then(resp=>{

                                    this.comments.map(com=>{
                                        if(!com.parent_id && com.id === resp.data.comment.parent_id){
                                              //this condition is checking if reply is for parent comment then append it in parent's reply
                                              com.replies.splice(0,0,resp.data.comment);

                                        }else{
                                                //this part is checking if reply is for child comments then append it in child's replies
                                                if(com.replies){
                                                        com.replies.map(rep=>{
                                                                if(rep.id === resp.data.comment.parent_id){
                                                                    rep.replies.splice(0,0,resp.data.comment);
                                                                }
                                                        })
                                                }
                                        }
                                    })

                                    $("textarea[name='comment-reply']").val(' ');
                                    $(e.target).removeClass('disabled');
                                    // console.log(resp.data)
                                },(err)=>{
                                    //server error
                                    $("textarea[name='comment-reply']").val(' ');
                                    $(e.target).removeClass('disabled');
                                    if(err.response.status === 401) {
                                        window.location="/login";
                                    }
                                    if(err.response.status === 419) {
                                        location.reload();
                                        window.location="/login";
                                    }

                                }).catch(err=>{
                                    //request error
                                    $(e.target).removeClass('disabled');
                                    $("textarea[name='comment-reply']").val(' ');
                                    swal.fire(
                                        "Error?",
                                        err.message,
                                        "error",
                                    ).then(function(){
                                        location.reload();
                                    })

                                  });

                            }else{
                                Vue.set(this.validate,"comment_reply","Comment field can't be empty");
                            };


            },


            likeTheComment:function(e,comment_id){
                $(e.target).addClass('disabled')

                if(this.auth_id){
                    // console.log(!$(e.target).is(":checked"))
                    if(!$(e.target).is(":checked")) {
                        //    dislike the comment
                        console.log('comment disliked');
                        axios.get(`/like_comment/?user_id=${this.user_id}&comment_id=${comment_id}`)
                            .then(resp=>{
                                $(e.target).removeClass("disabled");
                                $(e.target).prop('checked',false);
                                let newCount=parseInt($(e.target).siblings('.comment_like_count').text())-1;
                                $(e.target).siblings('.comment_like_count').text(newCount);
                            },(serverErr)=>{
                                if(serverErr.response.status === 419){
                                    location.reload();
                                    window.location="/login";
                                }
                                $(e.target).removeClass("disabled")
                            }).catch(reqErr=>{
                            swal.fire(
                                'Oops...',
                                reqErr.message+ "Sorry! Please try again later",
                                "error"
                            ).then(function(){
                                location.reload();
                            })
                            $(e.target).removeClass("disabled")
                        })

                    }else{
                        // like the comment
                        axios.get(`/like_comment/?user_id=${this.user_id}&comment_id=${comment_id}`)
                            .then(resp=>{
                                $(e.target).removeClass("disabled")
                                $(e.target).prop('checked',true);
                                let newCount=parseInt($(e.target).siblings('.comment_like_count').text())+1;
                                $(e.target).siblings('.comment_like_count').text(newCount);

                            },(serverErr)=>{
                                if(serverErr.response.status === 419){
                                    location.reload();
                                    window.location="/login";
                                }
                                $(e.target).removeClass("disabled")
                            }).catch(reqErr=>{
                            swal.fire(
                                'Oops...',
                                reqErr.message+ "Sorry! Please try again later",
                                "error"
                            ).then(function(){
                                location.reload();
                            })
                            $(e.target).removeClass("disabled")
                        })
                    }




                }else{
                    window.location="/login";
                }

            },

            findCommentLikeForUser:function(comment,user_id){
                let comment_user;
                if(comment.likes){
                   comment_user = comment.likes.find(like=> {return like.user_id == user_id })
                }
                return comment_user?comment_user.user_id:null;
            },
            findCommentLikeForComment:function(comment){
                let likes;
                if(comment.likes){
                    likes=comment.likes.find(like=> {return like.comment_id == comment.id});
                }
                return likes?likes.comment_id:null;
            },
            checkVar:function(data){
                this.test_debug=data;
            }





        },


}
</script>
