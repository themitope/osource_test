<?php
                                  switch ($request['status']) {
                                    case 0:
                                     echo "<button type='button' 
                                     id='".$request['unique_id']."' 
                                     class='btn btn-secondary btn-sm edit_request_details' 
                                     data-bank_name2='".$request['bank_name']."' 
                                     data-account_name2='".$request['account_name']."'
                                     data-account_number2='".$request['account_number']."'
                                     data-amount='".$request['amount']."'
                                     data-base_currency='".$request['base_currency']."'
                                     data-quote_currency='".$request['quote_currency']."'
                                     data-time_frame='".$request['time_frame']."'
                                     data-rate='".$request['rate']."'
                                     >Edit Request</button>";

                                     echo "<button type='button' id='".$request['unique_id']."' class='btn btn-danger btn-sm ml-1 delete_request_modal'>Delete Request</button>";
                                    break;

                                    case 1:
                                      echo "<button type='button' id='".$request['unique_id']."' class='btn btn-success btn-sm ml-1 upload_pop_modal'>Upload Payment Proof</button>";
                                    break;
                                    case 2:
                                    echo '<button class="btn btn-success btn-sm dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Take Action</button>';
                                      echo '<div class="dropdown-menu" aria-labelledby="dropdownMenu2">';
                                      echo "<button type='button' id='".$request['unique_id']."' class='ml-1 mb-1 view_counter_modal dropdown-item' 
                                      data-counter_time='".$request['counter_time'].' hour(s)'."' 
                                      data-counter_rate='".$request['counter_rate'].'/'.$get_request['quote_currency']."'>View Details</button>";
                                      echo "<button type='button' id='".$request['unique_id']."' class='ml-1 mb-1 accept_request_modal dropdown-item' data-request_id='".$request['request_id']."'>Accept</button>";
                                      echo "<button type='button' id='".$request['unique_id']."' class='ml-1 mb-1 counter_request_modal dropdown-item' data-amount='".$request['counter_time']."' data-rate='".$request['counter_rate']."'>Counter</button>";
                                      echo "<button type='button' id='".$request['unique_id']."' class='ml-1 mb-1 reject_request_modal dropdown-item'>Reject</button>";
                                      echo '<div>';
                                    break; 

                                    case 4:
                                      echo "<button type='button' id='".$request['unique_id']."' class='btn btn-danger btn-sm ml-1 delete_request_modal'>Completed</button>";
                                    break;                               
                                    default:
                                    break;
                                  }
                                ?>