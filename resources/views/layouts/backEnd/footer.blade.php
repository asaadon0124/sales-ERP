<footer class="main-footer">
    <strong>Copyright &copy; 2014-2019 <a href="http://divstark.com/cv">ENG : Ahmed Saadon</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.0.0-rc.1
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('assets/backEnd/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('assets/backEnd/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 rtl -->
<script src="https://cdn.rtlcss.com/bootstrap/v4.2.1/js/bootstrap.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/backEnd/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('assets/backEnd/plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('assets/backEnd/plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
{{-- <script src="{{ asset('assets/backEnd/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('assets/backEnd/plugins/jqvmap/maps/jquery.vmap.world.js') }}"></script> --}}
<!-- jQuery Knob Chart -->
<script src="{{ asset('assets/backEnd/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('assets/backEnd/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/backEnd/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('assets/backEnd/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('assets/backEnd/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('assets/backEnd/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/backEnd/dist/js/adminlte.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('assets/backEnd/dist/js/pages/dashboard.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('assets/backEnd/dist/js/demo.js') }}"></script>
{{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> --}}

<script>
    window.addEventListener('createModalToggle', event =>
    {
        $('#createModal').modal('toggle');

    })

    window.addEventListener('updateModalToggle', event =>
    {
        $('#updateModal').modal('toggle');
    })

    window.addEventListener('deleteModalToggle', event => {
        $('#deleteModal').modal('toggle');
    })

    window.addEventListener('showModalToggle', event => {
        $('#showModal').modal('toggle');
    })

    window.addEventListener('aproveModalToggle', event => {
        $('#aproveModal').modal('toggle');
    })

    window.addEventListener('restoreModalToggle', event => {
        $('#restoreModal').modal('toggle');
    })


    //  window.location.href = '/treasuries/' + data.id;

    // Livewire.on('changeStatus', data => {
    //     console.log('Received changeStatus event:', data);
    // });
</script>


@yield('js')
</body>
</html>
