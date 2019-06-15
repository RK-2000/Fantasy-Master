var express = require('express');
var bodyParser = require('body-parser');

var app = express();
var port = 3000;
var Request = require("request");
var localStorage = require('localStorage');

app.use('/static', express.static('public'))


var userLocalData = [];
var intervalBidTime;
var getAuctionLive;
var base_url = 'https://159.65.135.44/';
//   var base_url = 'http://localhost/527-fsl11/';
var cors = require('cors')

app.use(function(request, response, next) {
    response.header("Access-Control-Allow-Origin", "*");
    response.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
    next();
});

// parse application/x-www-form-urlencoded
app.use(bodyParser.urlencoded({ extended: true, limit:'500mb' }))

// parse application/json
app.use(bodyParser.json())

var http = require('http').Server(app);
var io = require('socket.io')(http);
io.on('connection', function(socket){
  if(!intervalBidTime){

    intervalBidTime = setInterval(function () { 
      Request.post({
        "headers": { "content-type": "application/json" },
        "url": base_url+"api/auctionDrafts/auctionBidTimeManagement",
        "body": ''
      }, (error, response, body) => {

        if(error) {
          //return console.dir(error);
        }
        playerBidTimeData = JSON.parse(body);
        console.log('dff')
        console.log(playerBidTimeData);
        
        if(playerBidTimeData.ResponseCode == 200 && playerBidTimeData.Data.length > 0){
          for (let i = 0; i < playerBidTimeData.Data.length; i++) {
            io.in('auction_'+playerBidTimeData.Data[i].ContestGUID).emit('TimeDifference', { ContestGUID: playerBidTimeData.Data[i].ContestGUID, 'timer' : (15-playerBidTimeData.Data[i].TimeDifference)});
           
            let ContestGUID = playerBidTimeData.Data[i].ContestGUID;
            if(playerBidTimeData.Data[i].AuctionIsBreakTimeStatus == 'Yes' && playerBidTimeData.Data[i].AuctionTimeBreakAvailable == 'No'){
              //console.log(playerBidTimeData.Data[i].BreakTimeInSec);
              if(playerBidTimeData.Data[i].BreakTimeInSec >= 300){
                //console.log('playerBidTimeData.Data[i].BreakTimeInSec');
                Request.post({
                  "headers": { "content-type": "application/json" },
                  "url": base_url+"api/auctionDrafts/auctionOnBreak",
                  "body": JSON.stringify({
                      "ContestGUID": playerBidTimeData.Data[i].ContestGUID,
                      "AuctionIsBreakTimeStatus": 'No',
                      "AuctionTimeBreakAvailable": 'No'     
                  })
                  }, (error, response, body) => {
                  if(error) {
                      //return console.dir(error);
                  }
                  //console.dir(body);
                  auctionOnBreak = JSON.parse(body);
                  if(auctionOnBreak.ResponseCode == 200){
                    io.in('auction_'+playerBidTimeData.Data[i].ContestGUID).emit('breakTimeEnd', { ContestGUID: playerBidTimeData.Data[i].ContestGUID, 'breakTime' : auctionOnBreak, breakTimeStart: 'No'});
                  }
                });
              }
            }else{
              //console.log(playerBidTimeData.Data[i].ContestGUID);
              //console.log('playerBidTimeData.Data[i].ContestGUID');
              if(playerBidTimeData.Data[i].TimeDifference >= 14 && playerBidTimeData.Data[i].PlayerStatus == 'Live'){

                var PlayerStatus = '';
                if(playerBidTimeData.Data[i].IsSold == 'UpcomingSold'){
                  PlayerStatus = 'Sold';
                }

                if(playerBidTimeData.Data[i].IsSold == 'UpcomingUnSold'){
                  PlayerStatus = 'Unsold';
                }
                //console.log(PlayerStatus, 'PlayerStatus')
                Request.post({
                  "headers": { "content-type": "application/json" },
                  "url": base_url+"api/auctionDrafts/auctionPlayerStausUpdate",
                  "body": JSON.stringify({
                      "SeriesGUID": playerBidTimeData.Data[i].SeriesGUID,
                      "PlayerStatus": PlayerStatus,
                      "ContestGUID": playerBidTimeData.Data[i].ContestGUID,
                      "PlayerGUID": playerBidTimeData.Data[i].PlayerGUID      
                  })
                }, (error, response, body) => {
                  if(error) {
                      //return console.dir(error);
                  }
                  
                  //console.log('auctionPlayerStausData')
                  auctionPlayerStausData = JSON.parse(body);
                  PlayerStatusCheck  = auctionPlayerStausData.Data.Status;
                  //console.log(auctionPlayerStausData)
                  if(auctionPlayerStausData.ResponseCode == 200){
                    if(auctionPlayerStausData.Data.AuctionStatus == 'Completed'){
                      Request.post({
                        "headers": { "content-type": "application/json" },
                        "url": base_url+"api/auctionDrafts/getPlayerBid",
                        "body": JSON.stringify({
                            "Params": 'PlayerID,PlayerRole,PlayerPic,PlayerCountry,PlayerBornPlace,PlayerBattingStyle,PlayerBowlingStyle,MatchType,MatchNo,MatchDateTime,SeriesName,TeamGUID,PlayerBattingStats,PlayerBowlingStats,IsPlaying,PointsData,PlayerSalary,TeamNameShort,PlayerSalaryCredit',
                            "ContestGUID": playerBidTimeData.Data[i].ContestGUID,
                            "SeriesGUID": playerBidTimeData.Data[i].SeriesGUID      
                        })
                      }, (error, response, body) => {
                        if(error) {
                            //return console.dir(error);
                        }

                        //console.dir(body);
                        //console.dir('body110000');
                        playerBidData = JSON.parse(body);
                        
                        playerBidTimeData.Data[i].playerBidData = playerBidData;
                        io.in('auction_'+playerBidTimeData.Data[i].ContestGUID).emit('AuctionPlayerChange', { ContestGUID: playerBidTimeData.Data[i].ContestGUID,PlayerGUID:playerBidTimeData.Data[i].playerBidData.Data.PlayerGUID, getBidPlayerData: playerBidTimeData.Data[i].playerBidData,PlayerStatus:auctionPlayerStausData.Data.Status});
                        //console.log(playerBidData);
                        if(playerBidTimeData.Data[i].playerBidData.ResponseCode == 200){
                            //console.dir(body);
                            //console.dir('body1');
                            //console.dir(auctionPlayerStausData.Data.AuctionStatus);
                            auctionPlayerStausData = JSON.parse(body);

                            Request.post({
                              "headers": { "content-type": "application/json" },
                              "url": base_url+"api/auctionDrafts/getPlayers",
                              "body": JSON.stringify({
                                  "SeriesGUID": playerBidTimeData.Data[i].SeriesGUID,
                                  "ContestGUID": playerBidTimeData.Data[i].ContestGUID,
                                  "Params": 'PlayerStatus,PlayerID,PlayerRole,PlayerPic,PlayerCountry,PlayerBornPlace,PlayerBattingStyle,PlayerBowlingStyle,MatchType,MatchNo,MatchDateTime,SeriesName,TeamGUID,PlayerBattingStats,PlayerBowlingStats,IsPlaying,PointsData,PlayerSalary,TeamNameShort,PlayerSalaryCredit,BidSoldCredit,PlayerStatus'     
                              })
                            }, (error, response, body) => {
                              if(error) {
                                  //return console.dir(error);
                              }
                              //console.dir(body);
                              auctionGetPlayer = JSON.parse(body);
                              io.in('auction_'+playerBidTimeData.Data[i].ContestGUID).emit('AuctionPlayerStatus', { ContestGUID: playerBidTimeData.Data[i].ContestGUID,PlayerGUID:playerBidTimeData.Data[i].playerBidData.Data.PlayerGUID, getBidPlayerData: playerBidTimeData.Data[i].playerBidData, auctionGetPlayer:auctionGetPlayer, auctionPlayerStausData:auctionPlayerStausData, sta:2, TimeDifference: playerBidTimeData.Data[i].TimeDifference, PlayerStatus:PlayerStatusCheck});
                              
                            });

                            Request.post({
                              "headers": { "content-type": "application/json" },
                              "url": base_url+"api/auctionDrafts/getJoinedContestsUsers",
                              "body": JSON.stringify({
                                "SeriesGUID": playerBidTimeData.Data[i].SeriesGUID,
                                "ContestGUID": playerBidTimeData.Data[i].ContestGUID,
                                "Params": 'FirstName,Username,UserGUID,ProfilePic,AuctionTimeBank,AuctionBudget,AuctionUserStatus'
                              })
                              }, (error, response, body) => {
                              if(error) {
                                  //return console.dir(error);
                              }
                              //console.dir(body);
                              auctionJoinedContestUser = JSON.parse(body);
                              io.in('auction_'+playerBidTimeData.Data[i].ContestGUID).emit('auctionJoinedContestUser', { ContestGUID: playerBidTimeData.Data[i].ContestGUID,PlayerGUID:playerBidTimeData.Data[i].playerBidData.Data.PlayerGUID, auctionJoinedContestUser: auctionJoinedContestUser});
                            });
                        }
                      });
                    }else{

                      Request.post({
                        "headers": { "content-type": "application/json" },
                        "url": base_url+"api/auctionDrafts/getPlayerBid",
                        "body": JSON.stringify({
                            "Params": 'PlayerID,PlayerRole,PlayerPic,PlayerCountry,PlayerBornPlace,PlayerBattingStyle,PlayerBowlingStyle,MatchType,MatchNo,MatchDateTime,SeriesName,TeamGUID,PlayerBattingStats,PlayerBowlingStats,IsPlaying,PointsData,PlayerSalary,TeamNameShort,PlayerSalaryCredit',
                            "ContestGUID": playerBidTimeData.Data[i].ContestGUID,
                            "SeriesGUID": playerBidTimeData.Data[i].SeriesGUID      
                        })
                      }, (error, response, body) => {
                        if(error) {
                            //return console.dir(error);
                        }
                        //console.log('============================================')
                         //console.log(playerBidTimeData.Data[i].ContestGUID)
                        //console.dir(body);
                        //console.dir('body11 123456');
                        playerBidData = JSON.parse(body);
                        

                        console.log(playerBidTimeData);
                        console.log('============================================')
                        playerBidTimeData.Data[i].playerBidData = playerBidData;
                        console.log(playerBidTimeData);
                        if(playerBidTimeData.Data[i].playerBidData.ResponseCode == 200){
                          if(playerBidTimeData.Data[i].AuctionTimeBreakAvailable == 'Yes'){
                            Request.post({
                              "headers": { "content-type": "application/json" },
                              "url": base_url+"api/auctionDrafts/auctionOnBreak",
                              "body": JSON.stringify({
                                  "ContestGUID": playerBidTimeData.Data[i].ContestGUID,
                                  "AuctionIsBreakTimeStatus": 'Yes',
                                  "AuctionTimeBreakAvailable": 'No'     
                              })
                            }, (error, response, body) => {
                              if(error) {
                                  //return console.dir(error);
                              }
                              //console.dir(body);
                              auctionOnBreak = JSON.parse(body);
                              if(auctionOnBreak.ResponseCode == 200){
                                io.in('auction_'+playerBidTimeData.Data[i].ContestGUID).emit('breakTimeStart', { ContestGUID: playerBidTimeData.Data[i].ContestGUID, 'breakTime' : auctionOnBreak, breakTimeStart: 'Yes'});
                              }
                            });
                          }else{

                            Request.post({
                              "headers": { "content-type": "application/json" },
                              "url": base_url+"api/auctionDrafts/auctionPlayerStausUpdate",
                              "body": JSON.stringify({
                                  "SeriesGUID": playerBidTimeData.Data[i].SeriesGUID,
                                  "PlayerStatus": 'Live',
                                  "ContestGUID": playerBidTimeData.Data[i].ContestGUID,
                                  "PlayerGUID": playerBidTimeData.Data[i].playerBidData.Data.PlayerGUID      
                              })
                            }, (error, response, body) => {
                              if(error) {
                                  //return console.dir(error);
                              }
                              //console.dir(body);
                              //console.dir('body19999');
                              
                              auctionPlayerStausData = JSON.parse(body);
                              io.in('auction_'+playerBidTimeData.Data[i].ContestGUID).emit('AuctionPlayerChange', { ContestGUID: playerBidTimeData.Data[i].ContestGUID,PlayerGUID:playerBidTimeData.Data[i].playerBidData.Data.PlayerGUID, getBidPlayerData: playerBidTimeData.Data[i].playerBidData,PlayerStatus:auctionPlayerStausData.Data.Status});
                              //console.dir(auctionPlayerStausData);
                              //console.log(auctionPlayerStausData.ResponseCode);
                              if(auctionPlayerStausData.ResponseCode == 200 || auctionPlayerStausData.Data.AuctionStatus == 'Completed'){
                                //console.log('ghfhghfh')
                              Request.post({
                                "headers": { "content-type": "application/json" },
                                "url": base_url+"api/auctionDrafts/getPlayers",
                                "body": JSON.stringify({
                                    "SeriesGUID": playerBidTimeData.Data[i].SeriesGUID,
                                    "ContestGUID": playerBidTimeData.Data[i].ContestGUID,
                                    "Params": 'PlayerStatus,PlayerID,PlayerRole,PlayerPic,PlayerCountry,PlayerBornPlace,PlayerBattingStyle,PlayerBowlingStyle,MatchType,MatchNo,MatchDateTime,SeriesName,TeamGUID,PlayerBattingStats,PlayerBowlingStats,IsPlaying,PointsData,PlayerSalary,TeamNameShort,PlayerSalaryCredit,BidSoldCredit'     
                                })
                              }, (error, response, body) => {
                                if(error) {
                                    //return console.dir(error);
                                }
                                //console.dir(body);
                                //console.log('YES11111')
                                auctionGetPlayer = JSON.parse(body);
                                if(auctionGetPlayer.ResponseCode == 200 || auctionPlayerStausData.Data.AuctionStatus == 'Completed'){
                                  //console.log('Yes');
                                  //console.log(playerBidData);
                                  console.log('playerBidTimeData.Data[i]'+'===='+i);
                                  //console.log(playerBidTimeData.Data[i].playerBidData);
                                  console.log(playerBidTimeData.Data[i].ContestGUID);
                                  if(!playerBidTimeData.Data[i].ContestGUID){
                                    ContestGUID = playerBidTimeData.Data[i].ContestGUID;
                                  }
                                  if(typeof playerBidTimeData.Data[i].playerBidData == 'undefined'){
                                    playerBidTimeData.Data[i].playerBidData = playerBidData;
                                  }

                                  io.in('auction_'+playerBidTimeData.Data[i].ContestGUID).emit('AuctionPlayerStatus', { ContestGUID: playerBidTimeData.Data[i].ContestGUID,PlayerGUID:playerBidTimeData.Data[i].playerBidData.Data.PlayerGUID, getBidPlayerData: playerBidTimeData.Data[i].playerBidData, auctionGetPlayer:auctionGetPlayer, auctionPlayerStausData:auctionPlayerStausData, sta:2, TimeDifference: playerBidTimeData.Data[i].TimeDifference, PlayerStatus:auctionPlayerStausData.Data.Status});
                                }
                              });

                              Request.post({
                                "headers": { "content-type": "application/json" },
                                "url": base_url+"api/auctionDrafts/getJoinedContestsUsers",
                                "body": JSON.stringify({
                                  "SeriesGUID": playerBidTimeData.Data[i].SeriesGUID,
                                  "ContestGUID": playerBidTimeData.Data[i].ContestGUID,
                                  "Params": 'FirstName,Username,UserGUID,ProfilePic,AuctionTimeBank,AuctionBudget,AuctionUserStatus'
                                })
                                }, (error, response, body) => {
                                if(error) {
                                    //return console.dir(error);
                                }
                                //console.dir(body);
                                auctionJoinedContestUser = JSON.parse(body);
                                if(auctionJoinedContestUser.ResponseCode == 200){
                                  io.in('auction_'+playerBidTimeData.Data[i].ContestGUID).emit('auctionJoinedContestUser', { ContestGUID: playerBidTimeData.Data[i].ContestGUID,PlayerGUID:playerBidData.Data.PlayerGUID, auctionJoinedContestUser: auctionJoinedContestUser});
                                }
                              });
                            }
                          });
                          }
                        }
                      });
                    }
                  }
                });
              }else{
                if(playerBidTimeData.Data[i].PlayerStatus == 'Upcoming'){
                  if(playerBidTimeData.Data[i].AuctionTimeBreakAvailable == 'Yes'){
                    Request.post({
                      "headers": { "content-type": "application/json" },
                      "url": base_url+"api/auctionDrafts/auctionOnBreak",
                      "body": JSON.stringify({
                          "ContestGUID": playerBidTimeData.Data[i].ContestGUID,
                          "AuctionIsBreakTimeStatus": 'Yes',
                          "AuctionTimeBreakAvailable": 'No'     
                      })
                    }, (error, response, body) => {
                      if(error) {
                          //return console.dir(error);
                      }
                      //console.dir(body);
                      auctionOnBreak = JSON.parse(body);
                      if(auctionOnBreak.ResponseCode == 200){
                        io.in('auction_'+playerBidTimeData.Data[i].ContestGUID).emit('breakTimeStart', { ContestGUID: playerBidTimeData.Data[i].ContestGUID, 'breakTime' : auctionOnBreak, breakTimeStart: 'Yes'});
                      }
                    });
                  }else{
                    console.log('fghhhgj678888888888888')
                    Request.post({
                      "headers": { "content-type": "application/json" },
                      "url": base_url+"api/auctionDrafts/getPlayerBid",
                      "body": JSON.stringify({
                          "Params": 'PlayerID,PlayerRole,PlayerPic,PlayerCountry,PlayerBornPlace,PlayerBattingStyle,PlayerBowlingStyle,MatchType,MatchNo,MatchDateTime,SeriesName,TeamGUID,PlayerBattingStats,PlayerBowlingStats,IsPlaying,PointsData,PlayerSalary,TeamNameShort,PlayerSalaryCredit',
                          "ContestGUID": playerBidTimeData.Data[i].ContestGUID,
                          "SeriesGUID": playerBidTimeData.Data[i].SeriesGUID      
                      })
                    }, (error, response, body) => {
                      if(error) {
                          //return console.dir(error);
                      }
                      
                      playerBidData = JSON.parse(body);
                      playerBidTimeData.Data[i].playerBidData = playerBidData;
                      console.log(playerBidTimeData.Data[i].playerBidData.ResponseCode+'==329');
                      if(playerBidTimeData.Data[i].playerBidData.ResponseCode == 200){
                        console.log(playerBidTimeData.Data[i].SeriesGUID);
                        console.log(playerBidTimeData.Data[i].ContestGUID);
                        console.log(playerBidTimeData.Data[i].playerBidData.Data.PlayerGUID);
                          Request.post({
                            "headers": { "content-type": "application/json" },
                            "url": base_url+"api/auctionDrafts/auctionPlayerStausUpdate",
                            "body": JSON.stringify({
                                "SeriesGUID": playerBidTimeData.Data[i].SeriesGUID,
                                "PlayerStatus": 'Live',
                                "ContestGUID": playerBidTimeData.Data[i].ContestGUID,
                                "PlayerGUID": playerBidTimeData.Data[i].playerBidData.Data.PlayerGUID      
                            })
                          }, (error, response, body) => {
                            if(error) {
                                //return console.dir(error);
                            }
                            //console.dir(body);
                            //console.dir('body222');
                            auctionPlayerStausData = JSON.parse(body);
                            io.in('auction_'+playerBidTimeData.Data[i].ContestGUID).emit('AuctionPlayerChange', { ContestGUID: playerBidTimeData.Data[i].ContestGUID,PlayerGUID:playerBidTimeData.Data[i].playerBidData.Data.PlayerGUID, getBidPlayerData: playerBidTimeData.Data[i].playerBidData,PlayerStatus:auctionPlayerStausData.Data.Status});
                            //console.log(auctionPlayerStausData)
                            if(auctionPlayerStausData.ResponseCode == 200){
                            
                              Request.post({
                                "headers": { "content-type": "application/json" },
                                "url": base_url+"api/auctionDrafts/getPlayers",
                                "body": JSON.stringify({
                                    "SeriesGUID": playerBidTimeData.Data[i].SeriesGUID,
                                    "ContestGUID": playerBidTimeData.Data[i].ContestGUID,
                                    "Params": 'PlayerStatus,PlayerID,PlayerRole,PlayerPic,PlayerCountry,PlayerBornPlace,PlayerBattingStyle,PlayerBowlingStyle,MatchType,MatchNo,MatchDateTime,SeriesName,TeamGUID,PlayerBattingStats,PlayerBowlingStats,IsPlaying,PointsData,PlayerSalary,TeamNameShort,PlayerSalaryCredit,BidSoldCredit'     
                                })
                              }, (error, response, body) => {
                                if(error) {
                                    //return console.dir(error);
                                }
                                //console.dir(body);
                                auctionGetPlayer = JSON.parse(body);
                                if(auctionGetPlayer.ResponseCode == 200){
                                  if(typeof playerBidTimeData.Data[i].playerBidData == 'undefined'){
                                    playerBidTimeData.Data[i].playerBidData = playerBidData;
                                  }
                                  console.log(playerBidTimeData.Data[i].playerBidData);
                                  io.in('auction_'+playerBidTimeData.Data[i].ContestGUID).emit('AuctionPlayerStatus', { ContestGUID: playerBidTimeData.Data[i].ContestGUID,PlayerGUID:playerBidTimeData.Data[i].playerBidData.Data.PlayerGUID, getBidPlayerData: playerBidTimeData.Data[i].playerBidData, auctionGetPlayer:auctionGetPlayer, auctionPlayerStausData: auctionPlayerStausData, sta:3, TimeDifference: playerBidTimeData.Data[i].TimeDifference, PlayerStatus:auctionPlayerStausData.Data.Status});
                                }
                              });

                              Request.post({
                                "headers": { "content-type": "application/json" },
                                "url": base_url+"api/auctionDrafts/getJoinedContestsUsers",
                                "body": JSON.stringify({
                                  "SeriesGUID": playerBidTimeData.Data[i].SeriesGUID,
                                  "ContestGUID": playerBidTimeData.Data[i].ContestGUID,
                                  "Params": 'FirstName,Username,UserGUID,ProfilePic,AuctionTimeBank,AuctionBudget,AuctionUserStatus'
                                })
                                }, (error, response, body) => {
                                if(error) {
                                    //return console.dir(error);
                                }
                                //console.dir(body);
                                auctionJoinedContestUser = JSON.parse(body);
                                if(auctionJoinedContestUser.ResponseCode == 200){
                                  io.in('auction_'+playerBidTimeData.Data[i].ContestGUID).emit('auctionJoinedContestUser', { ContestGUID: playerBidTimeData.Data[i].ContestGUID,PlayerGUID:playerBidTimeData.Data[i].playerBidData.Data.PlayerGUID, auctionJoinedContestUser: auctionJoinedContestUser});
                                }
                              });
                            }
                          });
                        }
                    });
                  }
                }else{
                  if(playerBidTimeData.Data[i].PreAssistant == 'Yes' && playerBidTimeData.Data[i].AuctionTimeBreakAvailable == 'No' && playerBidTimeData.Data[i].AuctionIsBreakTimeStatus == 'No' && playerBidTimeData.Data[i].TimeDifference > 5){
                    Request.post({
                      "headers": { "content-type": "application/json" },
                      "url": base_url+"api/auctionDrafts/addAuctionPlayerBid",
                      "body": JSON.stringify({
                          "SeriesGUID": playerBidTimeData.Data[i].SeriesGUID,
                          "UserGUID": playerBidTimeData.Data[i].UserGUID,
                          "ContestGUID": playerBidTimeData.Data[i].ContestGUID,
                          "PlayerGUID": playerBidTimeData.Data[i].PlayerGUID,
                          "BidCredit": playerBidTimeData.Data[i].BidCredit,
                      })
                    }, (error, response, body) => {
                      if(error) {
                          //return console.dir(error);
                      }
                      //console.dir(body);
                      responseData = JSON.parse(body);
                      console.log(responseData)
                      if(responseData.ResponseCode == 200){
                        io.to('auction_'+playerBidTimeData.Data[i].ContestGUID).emit('AuctionBidSuccess', { ContestGUID: playerBidTimeData.Data[i].ContestGUID,responseData:responseData});
                      }
                    });
                  }

                }
              }
            }
          }        
        }
      });
    },1000*5);
  }


  if(!getAuctionLive){
    getAuctionLive = setInterval(function () { 
      Request.post({
        "headers": { "content-type": "application/json" },
        "url": base_url+"api/auctionDrafts/getAuctionGameInLive",
        "body": ''
      }, (error, response, body) => {
        if(error) {
            //return console.dir(error);
        }
        //console.log(response);
        responseData = JSON.parse(body);
        if(responseData.ResponseCode == 200 && responseData.Data.Data.TotalRecords > 0 && responseData.Data.Data.Records.length > 0){
          //console.log(responseData.Data.Data.Records)
          for (let i = 0; i < responseData.Data.Data.Records.length; i++) {
            //console.log(responseData.Data.Data.Records[i].ContestGUID);
            Request.post({
                "headers": { "content-type": "application/json" },
                "url": base_url+"api/auctionDrafts/getAuctionGameStatusUpdate",
                "body": JSON.stringify({
                    "ContestGUID": responseData.Data.Data.Records[i].ContestGUID,
                    "Status": "Running"
                })
              }, (error, response, body) => {
              if(error) {
                //return console.dir(error);
              }

              getPlayersStatus = JSON.parse(body);
              //console.log(getPlayersStatus)
              if(getPlayersStatus.ResponseCode == 200){
                Request.post({
                  "headers": { "content-type": "application/json" },
                  "url": base_url+"api/auctionDrafts/getPlayerBid",
                  "body": JSON.stringify({
                      "Params": 'PlayerID,PlayerRole,PlayerPic,PlayerCountry,PlayerBornPlace,PlayerBattingStyle,PlayerBowlingStyle,MatchType,MatchNo,MatchDateTime,SeriesName,TeamGUID,PlayerBattingStats,PlayerBowlingStats,IsPlaying,PointsData,PlayerSalary,TeamNameShort,PlayerSalaryCredit',
                      "ContestGUID": responseData.Data.Data.Records[i].ContestGUID,
                      "SeriesGUID": responseData.Data.Data.Records[i].SeriesGUID      
                  })
                }, (error, response, body) => {
                  if(error) {
                      //return console.dir(error);
                  }
                  //console.dir(body);
                  playerBidData = JSON.parse(body);
                  
                  if(playerBidData.ResponseCode == 200){
                      Request.post({
                        "headers": { "content-type": "application/json" },
                        "url": base_url+"api/auctionDrafts/auctionPlayerStausUpdate",
                        "body": JSON.stringify({
                            "SeriesGUID": responseData.Data.Data.Records[i].SeriesGUID,
                            "PlayerStatus": 'Live',
                            "ContestGUID": responseData.Data.Data.Records[i].ContestGUID,
                            "PlayerGUID": playerBidData.Data.PlayerGUID      
                        })
                      }, (error, response, body) => {
                        if(error) {
                            //return console.dir(error);
                        }
                        //console.dir(body);
                        //console.dir('body37777777');
                        auctionPlayerStausData = JSON.parse(body);
                        io.in('auction_'+responseData.Data.Data.Records[i].ContestGUID).emit('AuctionPlayerChange', { ContestGUID: responseData.Data.Data.Records[i].ContestGUID,PlayerGUID:playerBidData.Data.PlayerGUID, getBidPlayerData: playerBidData,PlayerStatus:auctionPlayerStausData.Data.Status});

                        //console.log(auctionPlayerStausData)
                        if(auctionPlayerStausData.ResponseCode == 200){
                        
                          Request.post({
                              "headers": { "content-type": "application/json" },
                              "url": base_url+"api/auctionDrafts/getPlayers",
                              "body": JSON.stringify({
                                  "SeriesGUID": responseData.Data.Data.Records[i].SeriesGUID,
                                  "ContestGUID": responseData.Data.Data.Records[i].ContestGUID,
                                  "Params": 'PlayerStatus,PlayerID,PlayerRole,PlayerPic,PlayerCountry,PlayerBornPlace,PlayerBattingStyle,PlayerBowlingStyle,MatchType,MatchNo,MatchDateTime,SeriesName,TeamGUID,PlayerBattingStats,PlayerBowlingStats,IsPlaying,PointsData,PlayerSalary,TeamNameShort,PlayerSalaryCredit,BidSoldCredit'     
                              })
                            }, (error, response, body) => {
                              if(error) {
                                  //return console.dir(error);
                              }
                              //console.dir(body);
                              auctionGetPlayer = JSON.parse(body);
                              if(auctionPlayerStausData.ResponseCode == 200){
                                //console.log('Yes3');
                                io.in('auction_'+responseData.Data.Data.Records[i].ContestGUID).emit('AuctionPlayerStatus', { ContestGUID: responseData.Data.Data.Records[i].ContestGUID,PlayerGUID:playerBidData.Data.PlayerGUID, getBidPlayerData: playerBidData, auctionGetPlayer:auctionGetPlayer, sta:1, auctionPlayerStausData: auctionPlayerStausData, TimeDifference: 20, PlayerStatus:auctionPlayerStausData.Data.Status});
                              }
                            });

                            Request.post({
                              "headers": { "content-type": "application/json" },
                              "url": base_url+"api/auctionDrafts/getJoinedContestsUsers",
                              "body": JSON.stringify({
                                "SeriesGUID": responseData.Data.Data.Records[i].SeriesGUID,
                                "ContestGUID": responseData.Data.Data.Records[i].ContestGUID,
                                "Params": 'FirstName,Username,UserGUID,ProfilePicFirstName,Username,UserGUID,ProfilePic,AuctionTimeBank,AuctionBudget,AuctionUserStatus'
                              })
                              }, (error, response, body) => {
                              if(error) {
                                  //return console.dir(error);
                              }
                              //console.dir(body);
                              auctionJoinedContestUser = JSON.parse(body);
                              if(auctionJoinedContestUser.ResponseCode == 200){
                                io.in('auction_'+responseData.Data.Data.Records[i].ContestGUID).emit('auctionJoinedContestUser', { ContestGUID: responseData.Data.Data.Records[i].ContestGUID,PlayerGUID:playerBidData.Data.PlayerGUID, auctionJoinedContestUser: auctionJoinedContestUser});
                              }
                            });
                        }
                      });
                    }
                });
              }
              //console.dir(body);
            });

            io.in('auction_'+responseData.Data.Data.Records[i].ContestGUID).emit('AuctionStart', { ContestGUID: responseData.Data.Data.Records[i].ContestGUID });
          }
        }
      });
    }, 1000*5);
  }
  
  socket.on('AuctionName', function (data) {
    //console.log(JSON.stringify(data)+'---');
    userLocal = data;
    userLocal.socketId = socket.id
    userLocalData.push(userLocal);
    localStorage.setItem('userLocalData', JSON.stringify(userLocalData));
    socket.leave('auction_'+data.ContestGUID);
    socket.join('auction_'+data.ContestGUID);
    //console.log(data.ContestGUID)
    Request.post({
      "headers": { "content-type": "application/json" },
      "url": base_url+"api/auctionDrafts/changeUserStatus",
      "body": JSON.stringify({
          "ContestGUID": data.ContestGUID,
          "UserGUID": data.UserGUID,
          "AuctionUserStatus":'Online'
      })
    }, (error, response, body) => {
      if(error) {
          //return console.dir(error);
      }
      //console.dir(body);
      responseData = JSON.parse(body);
      if(responseData.ResponseCode == 200){
        
        Request.post({
          "headers": { "content-type": "application/json" },
          "url": base_url+"api/auctionDrafts/getJoinedContestsUsers",
          "body": JSON.stringify({
            "SeriesGUID": data.SeriesGUID,
            "ContestGUID": data.ContestGUID,
            "Params": 'FirstName,Username,UserGUID,ProfilePic,AuctionTimeBank,AuctionBudget,AuctionUserStatus'
          })
          }, (error, response, body) => {
          if(error) {
              //return console.dir(error);
          }
          //console.dir(body);
          auctionJoinedContestUser = JSON.parse(body);
          if(auctionJoinedContestUser.ResponseCode == 200){
            io.to('auction_'+data.ContestGUID).emit('auctionJoinedContestUser', { ContestGUID: data.ContestGUID, auctionJoinedContestUser:auctionJoinedContestUser});
          }
        });
      }
    });

  });

  

  socket.on("disconnect", function(data){
    console.log('socket+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++')
    //console.log(socket);

    userData = JSON.parse(localStorage.getItem('userLocalData'));
    
    index = userData.map(function(el) {return el.socketId;}).indexOf(socket.id);
    //console.log(index, 'index')
    if(index != -1){

      Request.post({
        "headers": { "content-type": "application/json" },
        "url": base_url+"api/auctionDrafts/changeUserStatus",
        "body": JSON.stringify({
            "ContestGUID": userData[index].ContestGUID,
            "UserGUID": userData[index].UserGUID,
            "AuctionUserStatus":'Offline'
        })
      }, (error, response, body) => {
        if(error) {
            //return console.dir(error);
        }
        //console.dir(body);
        
        responseData = JSON.parse(body);
        if(responseData.ResponseCode == 200){
          
          Request.post({
            "headers": { "content-type": "application/json" },
            "url": base_url+"api/auctionDrafts/getJoinedContestsUsers",
            "body": JSON.stringify({
              "SeriesGUID": userData[index].SeriesGUID,
              "ContestGUID": userData[index].ContestGUID,
              "Params": 'FirstName,Username,UserGUID,ProfilePic,AuctionTimeBank,AuctionBudget,AuctionUserStatus'
            })
            }, (error, response, body) => {
            if(error) {
                //return console.dir(error);
            }
            //console.dir(body);
            auctionJoinedContestUser = JSON.parse(body);
            if(auctionJoinedContestUser.ResponseCode == 200){
              io.to('auction_'+userData[index].ContestGUID).emit('auctionJoinedContestUser', { ContestGUID: userData[index].ContestGUID, auctionJoinedContestUser:auctionJoinedContestUser});
              //console.log(index, 'index')
              //socket.leave('auction_'+userData[index].ContestGUID);
              userData.splice(index, 1);
              localStorage.setItem('userLocalData', JSON.stringify(userData));
            }
          });
        }
      });
    }

  });


  socket.on('TimerHold', function (data) {
    let timerHoldData = data;
    //console.log(timerHoldData);
    if(timerHoldData.IsHold == 'Yes'){
      let time = parseInt(timerHoldData.Time);
        //console.log(time)
      setTimeout(function() {

        Request.post({
          "headers": { "content-type": "application/json" },
          "url": base_url+"api/auctionDrafts/changeUserContestStatusHoldOnOff",
          "body": JSON.stringify({
              "ContestGUID": timerHoldData.ContestGUID,
              "UserGUID": timerHoldData.UserGUID,
              "IsHold": 'No',
          })
        }, (error, response, body) => {
          if(error) {
              //return console.dir(error);
          }
          //console.dir(body);
          responseData = JSON.parse(body);
          if(responseData.ResponseCode == 200){
            Request.post({
              "headers": { "content-type": "application/json" },
              "url": base_url+"api/auctionDrafts/getJoinedContestsUsers",
              "body": JSON.stringify({
                "SeriesGUID": timerHoldData.SeriesGUID,
                "ContestGUID": timerHoldData.ContestGUID,
                "Params": 'FirstName,Username,UserGUID,ProfilePic,AuctionTimeBank,AuctionBudget,AuctionUserStatus'
              })
              }, (error, response, body) => {
              if(error) {
                  //return console.dir(error);
              }
            //console.dir(body);
              auctionJoinedContestUser = JSON.parse(body);
              if(auctionJoinedContestUser.ResponseCode == 200){
                io.to('auction_'+timerHoldData.ContestGUID).emit('auctionJoinedContestUser', { UserGUID:timerHoldData.UserGUID,ContestGUID: timerHoldData.ContestGUID, auctionJoinedContestUser:auctionJoinedContestUser, IsHold:'No', responseData : responseData});
              }
            });
            
          }
        });
      }, time*1000);
    }
    Request.post({
      "headers": { "content-type": "application/json" },
      "url": base_url+"api/auctionDrafts/changeUserContestStatusHoldOnOff",
      "body": JSON.stringify({
          "ContestGUID": timerHoldData.ContestGUID,
          "UserGUID": timerHoldData.UserGUID,
          "IsHold": timerHoldData.IsHold,
      })
    }, (error, response, body) => {
      if(error) {
          //return console.dir(error);
      }
      //console.dir(body);
      responseData = JSON.parse(body);
      if(responseData.ResponseCode == 200){
        Request.post({
          "headers": { "content-type": "application/json" },
          "url": base_url+"api/auctionDrafts/getJoinedContestsUsers",
          "body": JSON.stringify({
            "SeriesGUID": timerHoldData.SeriesGUID,
            "ContestGUID": timerHoldData.ContestGUID,
            "Params": 'FirstName,Username,UserGUID,ProfilePic,AuctionTimeBank,AuctionBudget,AuctionUserStatus'
          })
          }, (error, response, body) => {
          if(error) {
              //return console.dir(error);
          }
        //console.dir(body);
          auctionJoinedContestUser = JSON.parse(body);
          if(auctionJoinedContestUser.ResponseCode == 200){
            io.to('auction_'+timerHoldData.ContestGUID).emit('auctionJoinedContestUser', { UserGUID:timerHoldData.UserGUID,ContestGUID: timerHoldData.ContestGUID, auctionJoinedContestUser:auctionJoinedContestUser, IsHold:timerHoldData.IsHold, responseData : responseData});
          }
        });
        
      }
    });
  });

  
  socket.on('AuctionBid', function (data) {
    //console.log(data);
    Request.post({
      "headers": { "content-type": "application/json" },
      "url": base_url+"api/auctionDrafts/addAuctionPlayerBid",
      "body": JSON.stringify({
          "SeriesGUID": data.SeriesGUID,
          "SessionKey": data.SessionKey,
          "ContestGUID": data.ContestGUID,
          "PlayerGUID": data.PlayerGUID,
          "BidCredit": data.BidCredit,
      })
    }, (error, response, body) => {
      if(error) {
          //return console.dir(error);
      }
      //console.dir(body);
      responseData = JSON.parse(body);
      if(responseData.ResponseCode == 200){
        io.to('auction_'+data.ContestGUID).emit('AuctionBidSuccess', { ContestGUID: data.ContestGUID,responseData:responseData});
      }
    });
  });
});


var server = http.listen(port, function () {
  console.log('server is running '+port);
});