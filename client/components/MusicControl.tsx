import React, { useContext, useState } from 'react';
import SVG from 'react-inlinesvg';
import { PLAYERSTATUS } from '~enum/PlayerStatus';
import { MusicContext } from '~Contexts/MusicContext';
import Slider from '@material-ui/core/Slider';
import CurrentPlaying from '~components/CurrentPlaying/';
import CurrentPlayingArt from '~components/CurrentPlayingArt/';
import Rating from '~components/Rating/';

interface MusicControlProps {
    
}

const MusicControl: React.FC<MusicControlProps> = (props) => {
    const musicContext = useContext(MusicContext);

    const [ratingToggle, setRatingToggle] = useState(false);

    const [isSeeking, setIsSeeking] = useState(false);
    const [seekPosition, setSeekPosition] = useState(-1);

    const [value, setValue] = React.useState(30);

    const handleRatingToggle = (e) => {
        if (musicContext.currentPlayingSong !== undefined && musicContext.currentPlayingSong !== null) {
            setRatingToggle(!ratingToggle); 
        }
    }

    const formatLabel = (s) => ([
        (s - (s %= 60)) / 60 + (9 < s ? ':' : ':0') + s
        //https://stackoverflow.com/a/37770048
    ]);

    return (
        <div className={`${'musicControl'} ${ratingToggle ? 'ratingShown' : null}`}>
            <CurrentPlayingArt />
            
            <CurrentPlaying />
            
            <div className='ratingBarContainer'>
                <div className='ratingBar'>
                    <Rating value={musicContext.currentPlayingSong ? musicContext.currentPlayingSong.rating : 0} fav={musicContext.currentPlayingSong ? musicContext.currentPlayingSong.flag : 0}/>
                </div>
            </div>

            <div className='seekbar'>
                <Slider 
                    min={0}
                    max={musicContext.currentPlayingSong?.time ?? 0}
                    value={isSeeking ? seekPosition : musicContext.songPosition}
                    onChange={(_, value: number) => {
                        // setIsSeeking(true);
                        // setValue(value);
                        // setSeekPosition(value);
                        musicContext.seekSongTo(value);
                        // setIsSeeking(false);
                    }}
                    
                    disabled={musicContext.currentPlayingSong == undefined}
                    aria-labelledby="continuous-slider" 
                />
            </div>

            <div className='seekTimes'>
                <span className='seekStart'>{formatLabel(musicContext.songPosition)}</span>
                <span className='seekEnd'>{formatLabel(musicContext.currentPlayingSong?.time ?? 0)}</span>
            </div>
                    
            <div className='controls'>
                <div className='previousSong'>
                    <SVG
                        src={require('~images/icons/svg/previous-track.svg')}
                        alt='Back'
                        onClick={() => {
                            musicContext.playPrevious();
                        }}
                        className={`
                            ${'icon-button'} 
                            ${musicContext.songQueueIndex <= 0
                                ? 'disabled'
                                : ''}
                        `}
                    />
                </div>
                <div className='playPause'>
                    {musicContext.playerStatus === PLAYERSTATUS.STOPPED ||
                    musicContext.playerStatus === PLAYERSTATUS.PAUSED ? (
                        <SVG
                            src={require('~images/icons/svg/play.svg')}
                            alt='Play'
                            onClick={musicContext.playPause}
                            className={`
                                ${'icon-button'} 
                                ${musicContext.currentPlayingSong == undefined
                                    ? 'disabled'
                                    : ''}
                            `}
                        />
                    ) : (
                        <SVG
                            src={require('~images/icons/svg/pause.svg')}
                            alt='Pause'
                            onClick={musicContext.playPause}
                            className={`
                                ${'icon-button'} 
                                ${musicContext.currentPlayingSong == undefined
                                    ? 'disabled'
                                    : ''}
                            `}
                        />
                    )}
                </div>
                <div className='nextSong'>
                    <SVG
                        src={require('~images/icons/svg/next-track.svg')}
                        alt='Next'
                        onClick={() => {
                            musicContext.playNext();
                        }}
                        className={`
                            ${'icon-button'} 
                            ${musicContext.songQueueIndex ==
                            musicContext.songQueue.length - 1
                                ? 'disabled'
                                : ''}
                        `}
                    />
                </div>
            </div>
            
            <div className='secondaryControls'>
                <div className={`${'rating'} ${ratingToggle ? 'active' : null}`}>
                    <SVG
                        src={require('~images/icons/svg/star-full.svg')}
                        alt='Show ratings'
                        onClick={(e) => {
                            handleRatingToggle(e)
                        }}
                        className={`
                            ${'icon-button'} 
                        `}
                    />
                </div>

                <div className='shuffle'>
                    <SVG
                        src={require('~images/icons/svg/shuffle.svg')}
                        alt='Shuffle'
                        onClick={() => {
                            // TODO: shuffle;
                        }}
                        className={`
                            ${'icon-button'} 
                        `}
                    />
                </div>

                <div className='repeat'>
                    <SVG
                        src={require('~images/icons/svg/repeat.svg')}
                        alt='Repeat'
                        onClick={() => {
                            // TODO: repeat;
                        }}
                        className={`
                            ${'icon-button'} 
                        `}
                    />
                </div>

                <div className='moreOptions'>
                    <SVG
                        src={require('~images/icons/svg/more-options-hori.svg')}
                        alt='More options'
                        onClick={() => {
                            // TODO: open more options menu;
                        }}
                        className={`
                            ${'icon-button'} 
                        `}
                    />
                </div>
            </div>

            <div className='volumeSlide'>
                <SVG
                    src={require('~images/icons/svg/volume-up.svg')}
                    alt='Volume'
                    onClick={() => {
                        // TODO: toggle mute;
                    }}
                    className={`
                        ${'icon-button'} 
                    `}
                />
                <Slider
                    name='volume'
                    onChange={(event, value: number) => {
                        musicContext.setVolume(value);
                    }}
                    max={100}
                    min={0}
                    value={musicContext.volume}
                />
                
            </div>
        </div>
    );
};

export default MusicControl;