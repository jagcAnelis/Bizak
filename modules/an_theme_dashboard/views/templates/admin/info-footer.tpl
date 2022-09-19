
{$star = '<svg class=""
 xmlns="http://www.w3.org/2000/svg"
 xmlns:xlink="http://www.w3.org/1999/xlink"
 width="17px" height="16px">
<path fill-rule="evenodd"  fill="rgb(254, 213, 0)"
 d="M15.697,7.248 C16.012,6.927 16.124,6.458 15.988,6.021 C15.851,5.584 15.496,5.272 15.060,5.205 L11.179,4.618 C11.013,4.593 10.871,4.485 10.797,4.329 L9.062,0.666 C8.867,0.254 8.471,-0.001 8.031,-0.001 C7.591,-0.001 7.195,0.254 7.000,0.666 L5.265,4.329 C5.191,4.485 5.048,4.593 4.883,4.618 L1.002,5.206 C0.566,5.272 0.210,5.584 0.074,6.021 C-0.062,6.458 0.049,6.928 0.364,7.248 L3.172,10.099 C3.292,10.221 3.347,10.396 3.319,10.567 L2.656,14.593 C2.598,14.948 2.687,15.292 2.907,15.564 C3.249,15.988 3.846,16.117 4.324,15.856 L7.795,13.955 C7.940,13.875 8.122,13.876 8.267,13.955 L11.738,15.856 C11.907,15.948 12.087,15.995 12.273,15.995 C12.612,15.995 12.934,15.838 13.155,15.564 C13.375,15.292 13.464,14.947 13.405,14.593 L12.743,10.567 C12.714,10.396 12.769,10.221 12.889,10.099 L15.697,7.248 Z"/>
</svg>'}

<div class="an_panel an_panel_recommend ">
    <div class="an_panel_block-header">
        <h3>We recommend</h3>
    </div>	
    <div class="an_panel_recommend-list">
		
		{foreach from=$recommended item=item}
        <div class="an_panel_recommend-item">
            <div class="an_panel_recommend-item-logo">
				<img src="{$item.logo}" alt="{$item.name}" />
            </div>
            <div class="an_panel_recommend-item-content">
                <h2><a class="an_panel-link" href="{$item.url}" target="_blank">{$item.name}</a></h2>
                <div class="an_panel_recommend-item-info">
                    <div class="stars stars-{$item.rate}">
                        {$star}
						{$star}
						{$star}
						{$star}
						{$star}
                    </div>
                {*    <span>1.7.1 - 1.7.6.x</span> *}
                </div>
                <p>{$item.descr}</p>
            </div>
        </div>
		{/foreach}



    </div>
</div>