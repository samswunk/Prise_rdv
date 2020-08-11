function DateTimePickers() 
      {
          $.datetimepicker.setLocale('fr');
          $.datetimepicker.setDateFormatter('moment');
          
          $('.datepicker').datetimepicker({
            format:"DD/MM/YYYY",
            'showTimepicker': false
          });
          $('.timepicker').datetimepicker({
          format: 'HH:mm'
          });
          $('.datetimepicker').datetimepicker({
            format:"DD/MM/YYYY HH:mm"
          });
          $('.datepicker .xdsoft_timepicker').removeClass('active');
          $('.datepicker .xdsoft_timepicker').addClass('inactive');
          $('.timepicker .xdsoft_datepicker').removeClass('active');
          $('.timepicker .xdsoft_datepicker').addClass('inactive');
  
      }