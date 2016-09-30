@extends('layouts.admin')
@section('content')
<!-- Main content -->
<section class="content">
  <!-- row -->
  <div class="row">
    <div class="col-xs-12">
      <div class="box" id="app-content">
        <div class="box-header">
          <h3 class="box-title"> 【 @{{cur_title}} 】 </h3>
        </div>
        <!-- /.box-header -->

          <div class="box-body">
            <div class="form-group">
              <label >{{trans('admin.website_userrole_item_name')}} </label>
              <input type="text" id="name" class="form-control" v-model="params_data.name"  placeholder="{{trans('admin.website_demo_tip')}}：admin "  >
            </div>
            <div class="form-group">
              <label >{{trans('admin.website_userrole_item_display_name')}}</label>
              <input type="text" class="form-control" v-model="params_data.display_name"  placeholder="{{trans('admin.website_demo_tip')}}：管理员" >
            </div>
            <div class="form-group">
              <label >{{trans('admin.website_userrole_item_description')}}</label>
              <input type="text" class="form-control" v-model="params_data.description"  placeholder="{{trans('admin.website_demo_tip')}}：管理员会员组拥有全部权限" >
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <button v-if="params_data.id == 0" type="button" @click="add_action()" class="btn btn-primary" > <i class="fa fa-hand-peace-o"></i> {{trans('admin.website_action_save')}}</button>
            <button v-else type="button" @click="post_edit_action()" class="btn btn-primary" > <i class="fa fa-hand-peace-o"></i> {{trans('admin.website_action_save')}}</button>
            <button type="button" @click="back_action()" class="btn btn-primary" > <i class="fa fa-reply"></i> {{trans('admin.website_getback')}}</button>
          </div>

      </div>
      <!-- /.box -->
    </div>
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->

<script type="text/javascript">
Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name=csrf-token]').getAttribute('content')
Vue.http.options.emulateJSON = true;

new Vue({
    el: '#app-content',
    data: {
             apiurl_add:            '{{$website["apiurl_add"]}}', 
             apiurl_info:           '{{$website["apiurl_info"]}}', 
             apiurl_edit:           '{{$website["apiurl_edit"]}}', 
             params_data:
             {
                name                :'',
                display_name        :'',
                description         :'',
                id                  :'{{$website["id"]}}',
             },
             cur_title              :'',
             cur_title_add          :'{{trans("admin.website_action_add")}}',
             cur_title_edit         :'{{trans("admin.website_action_edit")}}',
          },
    ready: function (){ 
            //这里是vue初始化完成后执行的函数 
            if(this.params_data.id>0)
            {
                this.cur_title=this.cur_title_edit;
                document.getElementById("name").disabled=true;
                this.get_info_action();
            }
            else
            {
                this.cur_title=this.cur_title_add;
                document.getElementById("name").disabled=false;
            }
    }, 
    methods: 
    {
      //获取数据详情
      get_info_action:function()
      {
        this.$http.post(this.apiurl_info,this.params_data,
        {
          before:function(request)
          {
            loadi=layer.load("...");
          },
        })
        .then((response) => 
        {
          this.ready_info_action(response);
        },(response) => 
        {
          //响应错误
          var msg="{{trans('admin.website_outtime')}}";
          layermsg_error(msg);
        })
        .catch(function(response) {
          //异常抛出
          var msg="{{trans('admin.website_outtime_error')}}";
          layermsg_error(msg);
        })
      },
      //点击数据验证
      add_action:function()
      {
          if (this.params_data.name=='')
          {
              var msg="{{trans('admin.userrole_failure_tip1')}}";
              layermsg_error(msg);
          }
          else
          {
              this.post_add_action();
          }
      },
      //提交数据
      post_add_action:function()
      {
        this.$http.post(this.apiurl_add,this.params_data,{
          before:function(request)
          {
            loadi=layer.load("等待中...");
          },
        })
        .then((response) => 
        {
          this.return_info_action(response);

        },(response) => 
        {
          //响应错误
          var msg="{{trans('admin.website_outtime')}}";
          layermsg_error(msg);
        })
        .catch(function(response) {
          //异常抛出
          var msg="{{trans('admin.website_outtime_error')}}";
          layermsg_error(msg);
        })

      },
      //提交修改数据
      post_edit_action:function()
      {
        this.$http.post(this.apiurl_edit,this.params_data,{
          before:function(request)
          {
            loadi=layer.load("...");
          },
        })
        .then((response) => 
        {
          this.return_info_action(response);

        },(response) => 
        {
          //响应错误
          var msg="{{trans('admin.website_outtime')}}";
          layermsg_error(msg);
        })
        .catch(function(response) {
          //异常抛出
          var msg="{{trans('admin.website_outtime_error')}}";
          layermsg_error(msg);
        })
      },
      //返回信息处理
      return_info_action:function(response)
      {
        layer.close(loadi);
        var statusinfo=response.data;
        if(statusinfo.status==1)
        {
            if(statusinfo.is_reload==1)
            {
              layermsg_success_reload(statusinfo.info);
            }
            else
            {
              if(statusinfo.curl)
              {
                layermsg_s(statusinfo.info,statusinfo.curl);
              }
              else
              {
                layermsg_success(statusinfo.info);
              }
            }
        }
        else
        {
            if(statusinfo.curl)
            {
              layermsg_e(statusinfo.info,statusinfo.curl);
            }
            else
            {

              layermsg_error(statusinfo.info);
            }
        }
      },
      //处理初始化数据
      ready_info_action:function(response)
      {
        layer.close(loadi);
        var statusinfo=response.data;
        if(statusinfo.status==1)
        {
            this.params_data=statusinfo.resource;
        }
        else
        {
            if(statusinfo.curl)
            {
              layermsg_e(statusinfo.info,statusinfo.curl);
            }
            else
            {
              layermsg_error(statusinfo.info);
            }
        }
      },
      //点击返回
      back_action:function()
      {
        goback();
      }
    }               
})

</script>
@endsection