<?php 
    include('header.php');
?>

<!--Main container sec start-->
<div class="mainContainer" ng-controller="pointSystemController" ng-init="pointSystem()" ng-cloak >
    <div class="mrTop">
      <div class="pointSystem">
        <div class="container-fluid">
         
        <div class="top-header-title">
            <h1>Points System</h1>
        </div>
        <div class="pointContent">
            <div class="row">
                <div class="col-sm-8 offset-2">
                    <!-- tabular point system starts -->
                    <div class="transictionOption">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link {{activeTab=='T20' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="ChangeTab('T20'); ">T20</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{activeTab=='ODI' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="ChangeTab('ODI'); ">ODI</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{activeTab=='TEST' ? 'active' : '' }}" data-toggle="tab" href="javascript:void(0)" ng-click="ChangeTab('TEST');">TEST</a>
                            </li>
                        </ul>

                        <div class="tab-content">

                            <div id="T20" class="tab-pane {{activeTab=='T20' ? 'active' : '' }}">
                                
                                <div class="">
                                    <h4>Batting</h4>
                                    <div class="pointHead">
                                        <ul>
                                            <li>Type Of points</li>
                                            <li>T20</li>
                                        </ul>
                                    </div>

                                    <div class="pointBody">
                                        <ul ng-repeat="point in points | orderBy:'Sort'" ng-if="point.PointsInningType == 'Batting' && point.PointsT20!='0.0'" >
                                            <li>{{point.PointsTypeDescprition}}</li>
                                            <li>{{point.PointsT20 | number }}</li>
                                        </ul>
                                    </div>
                                    <h4>Bowling</h4>
                                    <div class="pointHead">
                                        <ul>
                                            <li>Type Of points</li>
                                            <li>T20</li>
                                        </ul>
                                    </div>

                                    <div class="pointBody">
                                        <ul ng-repeat="point in points | orderBy:'Sort'" ng-if="point.PointsInningType == 'Bowling' && point.PointsT20!='0.0'" >
                                            <li>{{point.PointsTypeDescprition}}</li>
                                            <li>{{point.PointsT20 | number }}</li>
                                        </ul>    
                                    </div>
                                    <h4>Fielding</h4>
                                    <div class="pointHead">
                                        <ul>
                                            <li>Type Of points</li>
                                            <li>T20</li>
                                        </ul>
                                    </div>

                                    <div class="pointBody">
                                        <ul ng-repeat="point in points | orderBy:'Sort'" ng-if="point.PointsInningType == 'Fielding' && point.PointsT20!='0.0'" >
                                            <li>{{point.PointsTypeDescprition}}</li>
                                            <li>{{point.PointsT20 | number }}</li>
                                        </ul>    
                                    </div>
                                    
                                </div>
                            </div>

                            <div id="ODI" class="tab-pane {{activeTab=='ODI' ? 'active' : '' }}">
                                
                                <div class="">
                                    <h4>Batting</h4>
                                    <div class="pointHead">
                                        <ul>
                                            <li>Type Of points</li>
                                            <li>ODI</li>
                                        </ul>
                                    </div>

                                    <div class="pointBody">
                                        <ul ng-repeat="point in points | orderBy:'Sort'" ng-if="point.PointsInningType == 'Batting' && point.PointsODI!='0.0'" >
                                            <li>{{point.PointsTypeDescprition}}</li>
                                            <li>{{point.PointsODI | number }}</li>
                                        </ul>
                                    </div>
                                    <h4>Bowling</h4>
                                    <div class="pointHead">
                                        <ul>
                                            <li>Type Of points</li>
                                            <li>ODI</li>
                                        </ul>
                                    </div>

                                    <div class="pointBody">
                                        <ul ng-repeat="point in points | orderBy:'Sort'" ng-if="point.PointsInningType == 'Bowling' && point.PointsODI!='0.0'" >
                                            <li>{{point.PointsTypeDescprition}}</li>
                                            <li>{{point.PointsODI | number }}</li>
                                        </ul>    
                                    </div>
                                    <h4>Fielding</h4>
                                    <div class="pointHead">
                                        <ul>
                                            <li>Type Of points</li>
                                            <li>ODI</li>
                                        </ul>
                                    </div>

                                    <div class="pointBody">
                                        <ul ng-repeat="point in points | orderBy:'Sort'" ng-if="point.PointsInningType == 'Fielding' && point.PointsODI!='0.0'" >
                                            <li>{{point.PointsTypeDescprition}}</li>
                                            <li>{{point.PointsODI | number }}</li>
                                        </ul>    
                                    </div>
                                    
                                </div>
                            </div>

                            <div id="TEST" class="tab-pane {{activeTab=='TEST' ? 'active' : '' }}">
                                
                                <div class="">
                                    <h4>Batting</h4>
                                    <div class="pointHead">
                                        <ul>
                                            <li>Type Of points</li>
                                            <li>TEST</li>
                                        </ul>
                                    </div>

                                    <div class="pointBody">
                                        <ul ng-repeat="point in points | orderBy:'Sort'" ng-if="point.PointsInningType == 'Batting' && point.PointsTEST!='0.0'" >
                                            <li>{{point.PointsTypeDescprition}}</li>
                                            <li>{{point.PointsTEST | number }}</li>
                                        </ul>
                                    </div>
                                    <h4>Bowling</h4>
                                    <div class="pointHead">
                                        <ul>
                                            <li>Type Of points</li>
                                            <li>TEST</li>
                                        </ul>
                                    </div>

                                    <div class="pointBody">
                                        <ul ng-repeat="point in points | orderBy:'Sort'" ng-if="point.PointsInningType == 'Bowling' && point.PointsTEST!='0.0'" >
                                            <li>{{point.PointsTypeDescprition}}</li>
                                            <li>{{point.PointsTEST | number }}</li>
                                        </ul>    
                                    </div>
                                    <h4>Fielding</h4>
                                    <div class="pointHead">
                                        <ul>
                                            <li>Type Of points</li>
                                            <li>TEST</li>
                                        </ul>
                                    </div>

                                    <div class="pointBody">
                                        <ul ng-repeat="point in points | orderBy:'Sort'" ng-if="point.PointsInningType == 'Fielding' && point.PointsTEST!='0.0'" >
                                            <li>{{point.PointsTypeDescprition}}</li>
                                            <li>{{point.PointsTEST | number }}</li>
                                        </ul>    
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            </div>
        </div>
    </div>
</div>
</div>



</div>
<!--Main container sec end-->
<?php include('innerFooter.php');?>