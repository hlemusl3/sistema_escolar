<?php require_once INCLUDES.'inc_header.php'; ?>
      <div class="container">

      <div class="col-xl-9">
        <div id="agenda">
        </div>
      </div>

      </div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('agenda');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: "es",

      headerToolbar: {
        left: 'prev, next today',
        center: 'title',
        right: 'dayGridMonth, timeGridWeek, listWeek'

      },
  
      events: [
        <?php foreach($d->tareas as $t) { ?>
        {
          id: '<?php echo $t->id; ?>',
          title: '<?php echo $t->titulo; ?>',
          start: '<?php echo $t->fecha_inicial; ?>',
          end: '<?php echo $t->fecha_disponible; ?>'
        },
        <?php  } ?>
      ]
    
    });
    calendar.render();
  });

</script>
      
<?php require_once INCLUDES.'inc_footer.php'; ?>