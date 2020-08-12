function DateTimePickers() 
{
    $.datetimepicker.setLocale('fr');
    $.datetimepicker.setDateFormatter('moment');
    
    $('.datetimepicker').datetimepicker({
      format:"DD/MM/YYYY HH:mm",
      onChangeDateTime:function(ct,$i)
      {
        var control = $i[0]['id'];
        var today = new Date();
        ct.setHours(ct.getHours() + 2);
        var d = getFormattedDate(new Date(ct));
        switch (control) 
        {
          case 'booking_start':
            $('#booking_end').val(d);
            $('#booking_end').datetimepicker({minDate: ct});
            break;
          /*case 'booking_end':
              console.log("Je mets max date de booking_start à "+$('#booking_end').val());
            $('#booking_start').val($('#booking_end').val());
            $('#booking_start').datetimepicker({maxDate: ct});
            break;*/
        }
      }
    });

    // $('.datetimepicker .start').datetimepicker({
    //   onSelectDate:function(ct,$i)
    //   {
    //    console.log("CHARGEMENT DE "+this.name);
    //    this.setOptions({
    //     maxDate:$('.datetimepicker .end').val()?$('.datetimepicker .end').val():false
    //    })
    //   }
    //  });

    //  $('.datetimepicker .end').datetimepicker({
    //   onSelectDate:function( ct )
    //   { console.log(this);
    //    this.setOptions({
    //     minDate:$('.datetimepicker .start').val()?$('.datetimepicker .start').val():false
    //    })
    //   }
    //  });
    $('.datepicker').datetimepicker({
      format:"DD/MM/YYYY",
      timepicker:false,
      onChangeDateTime:function(ct,$i)
      {
        var control = $i[0]['id'];
        console.log("je suis "+control)
        switch (control) 
        {
          case 'multi_start':
            $('#multi_end').val($('#multi_start').val());
            $('#multi_end').datetimepicker({minDate: ct});
            break;
          /*case 'booking_end':
              console.log("Je mets max date de booking_start à "+$('#booking_end').val());
            $('#booking_start').val($('#booking_end').val());
            $('#booking_start').datetimepicker({maxDate: ct});
            break;*/
        }
        $("#ConsoleDate").html("Du " + $('#multi_start').val() +" au "+$('#multi_end').val());
      }
    });
    $('.timepicker').datetimepicker({
      datepicker:false,
      format:'HH:mm',
      onChangeDateTime:function(ct,$i)
      {
        var control = $i[0]['id'];
        var today = new Date();
        ct.setHours(ct.getHours() + 2);
        var d = moment(ct).tz("Europe/Paris").format("HH:mm");
        switch (control) 
        {
          case 'multi_start_hour':
            $('#multi_end_hour').val(d);
            $('#multi_end_hour').datetimepicker({minDate: ct});
            break;
            /*case 'booking_end':
            console.log("Je mets max date de booking_start à "+$('#booking_end').val());
            $('#booking_start').val($('#booking_end').val());
            $('#booking_start').datetimepicker({maxDate: ct});
            break;*/
          }
          $("#ConsoleHeure").html("De " + $('#multi_start_hour').val() +" à "+$('#multi_end_hour').val());
      }
    });/**/
}