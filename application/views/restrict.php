<section style="min-height: calc(100vh - 83px);" class="light-bg">
    <div class="container">
	<div class="row">
            <div class=" col-lg-offset-3 col-lg-6 text-center">
		<div class="section-title">
                    <h1>AREA RESTRITA</h1>                                
		</div>
            </div>
	</div>
    </div>
    <div class="container">
	<div class="row">
            <div class=" col-lg-offset-5 col-lg-2 text-center">
		<div class="form-group">
                    <a id="btn_your_user" class="btn btn-link" user_id="<?=$userId?>"><i class="fa fa-user"></i></a>
                    <a class="btn btn-link" href="restrict/logoff"><i class="fa fa-sign-out"></i></a>                                  
		</div>
            </div>
	</div>
    </div>
    
    <div class="container">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-courses" role="tab" data-toggle="tab">Cursos</a></li>
            <li><a href="#tab-team" role="tab" data-toggle="tab">Equipe</a></li>
            <li><a href="#tab-users" role="tab" data-toggle="tab">Usuários</a></li>
        </ul>
        
        <div class="tab-content">
            <div id="tab-courses" class="tab-pane active">
                <div class="container-fluid">
                    <h1 class="text-center"><strong>Gerenciar Cursos</strong></h1>
                    <a id="btn_add_course" class="btn btn-primary"><i class="fa fa-plus">&nbsp; Adicionar Curso</i></a>
                    <table id="dt_courses" class="table table-striped table-bordered">
                        <thead>
                            <tr class="tableheader">
                                <th class="dt-center">Nome</th>
                                <th class="dt-center no-sort">Imagem</th>
                                <th class="dt-center">Duração</th>
                                <th class="no-sort">Descrição</th>
                                <th class="dt-center no-sort">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="tab-team" class="tab-pane">
                <div class="container-fluid">
                    <h1 class="text-center"><strong>Gerenciar Equipe</strong></h1>
                    
                    <a id="btn_add_member" class="btn btn-primary"><i class="fa fa-plus">&nbsp; Adicionar Integrante na equipe</i></a>
                    <table id="dt_team" class="table table-striped table-bordered">
                        <thead>
                            <tr class="tableheader">
                                <th class="dt-center">Nome</th>
                                <th class="dt-center no-sort">Foto</th>
                                <th class="no-sort">Descrição</th>
                                <th class="dt-center no-sort">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="tab-users" class="tab-pane">
                <div class="container-fluid">
                    <h1 class="text-center"><strong>Gerenciar Usuários</strong></h1>
                    
                    <a id="btn_add_user" class="btn btn-primary"><i class="fa fa-plus">&nbsp; Adicionar Usuário</i></a>
                    <table id="dt_users" class="table table-striped table-bordered">
                        <thead>
                            <tr class="tableheader">
                                <th>Nome Completo</th>
                                <th>Login</th>
                                <th>E-mail</th>
                                <th class="dt-center no-sort">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="modal_course" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">X</button>
                <h4 class="modal-title">Curso</h4>
            </div>
            <div class="modal-body">
                <form id="form_course">
                    <input id="course_id" name="courseId" hidden>
                    
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Nome</label>
                        <div class="col-lg-10">
                            <input id="course_name" name="courseName" class="form-control" maxlength="100">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Imagem</label>
                        
                        <div class="col-lg-10">
                            <img id="course_img_path" src="" style="max-height: 400px; max-width: 400px;">
                            <label class="btn btn-block btn-info">
                                <i class="fa fa-upload"></i>&nbsp; Importar Imagem
                                <input type="file" id="btn_upload_course_img" accept="image/*" style="display: none;">
                            </label>
                            
                            <input id="course_img" name="courseImg" hidden>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Duração (h)</label>
                        <div class="col-lg-10">
                            <input type="number" step="0.1" id="course_duration" name="courseDuration" class="form-control">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Descrição</label>
                        <div class="col-lg-10">
                            <textarea id="course_description" name="courseDescription" class="form-control"></textarea>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    
                    <div class="form-group text-center">
                        <button type="submit" id="btn_save_course" class="btn btn-primary">
                            <i class="fa fa-save">&nbsp; Salvar</i>
                        </button>
                        <span class="help-block"></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modal_member" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">X</button>
                <h4 class="modal-title">Membro</h4>
            </div>
            <div class="modal-body">
                <form id="form_member">
                    <input id="member_id" name="memberId" hidden>
                    
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Nome</label>
                        <div class="col-lg-10">
                            <input id="member_name" name="memberName" class="form-control" maxlength="100">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Foto</label>
                        <div class="col-lg-10">
                            <img id="member_photo_path" src="" style="max-height: 400px; max-width: 400px;">
                            <label class="btn btn-block btn-info">
                                <i class="fa fa-upload"></i>&nbsp; Importar Foto
                                <input type="file" id="btn_upload_member_photo" accept="image/*" style="display: none;">
                            </label>
                            
                            <input id="member_photo" name="memberPhoto" hidden>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Descrição</label>
                        <div class="col-lg-10">
                            <textarea id="member_description" name="memberDescription" class="form-control"></textarea>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    
                    <div class="form-group text-center">
                        <button type="submit" id="btn_save_member" class="btn btn-primary">
                            <i class="fa fa-save">&nbsp; Salvar</i>
                        </button>
                        <span class="help-block"></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modal_user" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">X</button>
                <h4 class="modal-title">Usuário</h4>
            </div>
            <div class="modal-body">
                <form id="form_user">
                    <input id="user_id" name="userId" hidden>
                    
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Login</label>
                        <div class="col-lg-10">
                            <input id="user_login" name="userLogin" class="form-control" maxlength="30">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Nome Completo</label>
                        <div class="col-lg-10">
                            <input id="user_full_name" name="userFullName" class="form-control" maxlength="100">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Email</label>
                        <div class="col-lg-10">
                            <input type="email" id="user_email" name="userEmail" class="form-control" maxlength="100">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Confirmar Email</label>
                        <div class="col-lg-10">
                            <input type="email" id="user_email_confirm" name="userEmailConfirm" class="form-control" maxlength="100">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Senha</label>
                        <div class="col-lg-10">
                            <input type="password" id="user_password" name="userPassword" class="form-control">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Senha</label>
                        <div class="col-lg-10">
                            <input type="password" id="user_password_confirm" name="userPasswordConfirm" class="form-control">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    
                    <div class="form-group text-center">
                        <button type="submit" id="btn_save_user" class="btn btn-primary">
                            <i class="fa fa-save">&nbsp; Salvar</i>
                        </button>
                        <span class="help-block"></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>