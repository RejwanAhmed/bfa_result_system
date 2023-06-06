<?php
// there is no use of $run where there is no search button. But in case of search button we have sent query in $run and then sent it to display_content for deafult page.
    function pagination($run,$page_name)
    {
        $pagination_buttons = 10;
        $page_number = (isset($_GET['page']) AND !empty($_GET['page'])) ? $_GET['page'] : 1;
        $per_page_records = 10;
        // Call get_row_count function to get the total number of row
        $rows = get_row_count();
        $last_page = ceil($rows/$per_page_records);
        $pagination = '';

        $pagination .= '<div class = "row justify-content-center"><nav>';
        $pagination .= '<ul class = "pagination">';

        if($page_number <= 1)
        {
            $page_number = 1;
        }
        else if($page_number>$last_page)
        {
            $page_number = $last_page;
        }
        // Call to display data of that page from where it has been called
        display_content($run,(($page_number-1)*10),$per_page_records,$page_number);

        $half =floor( $pagination_buttons/2);

        if($page_number < $pagination_buttons AND ($last_page == $pagination_buttons OR $last_page>$pagination_buttons))
        {
            for($i=1; $i<=$pagination_buttons; $i++)
            {
                if($i==$page_number)
                {
                    $pagination .= '<li class = "active page-item">';
                    $pagination .= '<a class = "page-link" href="'.$page_name.'.php?page='.$i.'">'.$i.' <span class = "sr-only">(current)</span></a>';
                    $pagination .= '</li>';
                }
                else
                {
                    $pagination .= '<li class = "page-item"><a class = "page-link" href="'.$page_name.'.php?page='.$i.'">'.$i.'</a></li>';
                }
            }
            if($last_page>$pagination_buttons)
            {
                $pagination .= '<li class = "page-item"><a class = "page-link" href="'.$page_name.'.php?page='.($pagination_buttons+1).'">&raquo;</a></li>';
            }
        }
        else if($page_number >= $pagination_buttons AND $last_page > $pagination_buttons)
        {
            if(($page_number+$half) >= $last_page)
            {
                $pagination .= '<li class = "page-item"><a class = "page-link" href="'.$page_name.'.php?page='.($last_page-$pagination_buttons).'">&laquo;</a></li>';

                for($i=($last_page - $pagination_buttons)+1 ; $i<=$last_page; $i++)
                {
                    if($i==$page_number)
                    {
                        $pagination .= '<li class = "active page-item">';
                        $pagination .= '<a class = "page-link" href="'.$page_name.'.php?page='.$i.'">'.$i.' <span class = "sr-only">(current)</span></a>';
                        $pagination .= '</li>';
                    }
                    else
                    {
                        $pagination .= '<li class = "page-item"><a class = "page-link" href="'.$page_name.'.php?page='.$i.'">'.$i.'</a></li>';
                    }
                }
            }
            else if(($page_number + $half) < $last_page)
            {
                $pagination .= '<li class = "page-item"><a class = "page-link" href="'.$page_name.'.php?page='.($page_number-$half-1).'">&laquo;</a></li>';
                for($i=($page_number - $half) ; $i<=($page_number + $half); $i++)
                {
                    if($i==$page_number)
                    {
                        $pagination .= '<li class = "active page-item">';
                        $pagination .= '<a class = "page-link" href="'.$page_name.'.php?page='.$i.'">'.$i.' <span class = "sr-only">(current)</span></a>';
                        $pagination .= '</li>';
                    }
                    else
                    {
                        $pagination .= '<li class = "page-item"><a class = "page-link" href="'.$page_name.'.php?page='.$i.'">'.$i.'</a></li>';
                    }
                }
                $pagination .= '<li class = "page-item"><a class = "page-link" href="'.$page_name.'.php?page='.($page_number+$half+1).'">&raquo;</a></li>';
            }

        }
        else if($page_number<$pagination_buttons AND  $last_page<$pagination_buttons)
        {
            for($i=1; $i<=$last_page; $i++)
            {
                if($i==$page_number)
                {
                    $pagination .= '<li class = "active page-item ">';
                    $pagination .= '<a class = "page-link" href="'.$page_name.'.php?page='.$i.'">'.$i.' <span class = "sr-only">(current)</span></a>';
                    $pagination .= '</li>';
                }
                else
                {
                    $pagination .= '<li class = "page-item"><a class = "page-link" href="'.$page_name.'.php?page='.$i.'">'.$i.'</a></li>';
                }
            }
        }
        $pagination .= '</ul>';
        $pagination .= '</nav></div>';
        echo $pagination;
    }
?>
