<script src="js/bootstrap/jquery.js"></script>
<script src="js/bootstrap/popper.min.js"></script>
<script src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/sweetalert2.js"></script>
<script src="js/app.js"></script>
<script>
    localStorage.setItem('admin',<?php echo $_SESSION['user']['admin']; ?>)
    localStorage.setItem('idLocal',<?php echo $_SESSION['user']['idLocal']; ?>)
    localStorage.setItem('idUsuario',<?php echo $_SESSION['user']['idUsuario']; ?>)
</script>