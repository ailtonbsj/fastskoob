<?php
if(isset($_POST['email'])){
  $email = $_POST['email'];
  $pass = $_POST['password'];
  session_start();
  $_SESSION['email'] = $email;
  $_SESSION['pass'] = $pass;
} else {
  session_destroy();
  header('location: index.php');
}
require('vendor/autoload.php');
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Custom CSS -->
    <link href="css/main.css" rel="stylesheet">
    <title></title>
  </head>
  <body>
        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">FastSkoob</a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li>
                            <a id="menu-sobre" href="#">Sobre</a>
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>

        <!-- Page Content -->
        <div class="container">

            <!-- Projects Row -->
            <div class="livro-mod row hidden">
              <div class="col-xs-6 col-md-3 portfolio-item" style="margin:0; height:285px;">
                  <a href="#" style="display:block;">
                      <img style="width:100px;height:131px; margin: auto;" class="livro-img img-responsive" src="http://placehold.it/200x250" alt="">
                  </a>
                  <h5 class="text-center">
                      <a class="livro-name" href="#">name</a>
                  </h5>
                  <p class="livro-hist text-center">hist.</p>

                  <div class="progress">
                    <div class="progress-bar" style="width: 0%;">
                      0%
                    </div>
                  </div>

              </div>
            </div>
            <div id="main-area" class="row">
              <center>
              <img src="loader.gif">
              </center>
            </div>
            <!-- /.row -->


            <!-- Modal -->
            <div class="modal fade" id="salveHist" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <strong>Salvar</strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="form-group">
                      <label>Lembrete</label>
                      <input type="text" id="hst-text" class="form-control" value="">
                    </div>
                    <div class="form-group">
                      <label>Pagina</label>
                      <input type="text" id="hst-pag" value="" class="form-control">
                    </div>
                    <div class="form-group">
                      <label>Nota</label>
                      <select id="hst-nota" class="form-control">
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                      </select>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button id="btn-hst-save" type="button" class="btn btn-primary">Salvar</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /Modal -->

            <hr>

            <!-- Footer -->
            <footer>
                <div class="row">
                    <div class="col-lg-12">
                        <p>Copyright &copy; Developed by Jose Ailton B. da Silva</p>
                    </div>
                </div>
                <!-- /.row -->
            </footer>

        </div>
        <!-- /.container -->


    <script src="vendor/components/jquery/jquery.min.js" charset="utf-8"></script>
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js" charset="utf-8"></script>
    <script type="text/javascript">
    function upPages(){
      var idbook = $(this).attr('data-id');
      $('#salveHist').modal();
      $('#btn-hst-save').click(function(){
        var text = $('#hst-text').val();
        var pag = $('#hst-pag').val();
        var note = $('#hst-nota').val();
        $.post('update.php',
        {id : idbook, texto : text, paginas : pag, nota : note},
        function(res){
          if(res.indexOf('salvo com sucesso') != -1){
            location.reload();
          }
            console.log(res);
        });
      });
    }
    $(function(){
      $.get('list.php', function(raw){
        console.log(raw);
        raw = JSON.parse(raw);
        if(raw.success){
          $('#main-area').empty();
          var livros = raw.response;
          for (livro of livros) {
            var template = $('.livro-mod').clone();
            $(template).find('.livro-name').html(livro.edicao.titulo);
            $(template).find('.livro-img').attr('src',livro.edicao.capa_pequena);
            $(template).find('.livro-hist').html('Paginas Lidas: '+livro.paginas_lidas)
            .attr('data-id', livro.edicao.id);
            var perc = Math.trunc(livro.paginas_lidas/livro.edicao.paginas*100)+'%';
            $(template).find('.progress-bar').css('width', perc).html(perc);
            $('#main-area').append($(template).html());
          }
          $('.livro-hist').click(upPages);
        } else {
          alert('Login invalido');
          window.location = 'index.php';
        }
      });
      $('#menu-sobre').click(function(){
        alert('Developed by José Ailton B. da Silva');
      });
    });
    </script>
  </body>
</html>
