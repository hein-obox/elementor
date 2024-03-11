import * as React from 'react';
import { SvgIcon, styled } from '@elementor/ui';

const YoutubeIcon = ( props ) => {
	return (
		<SvgIcon viewBox="0 0 24 24" { ...props }>
			<path fillRule="evenodd" clipRule="evenodd" d="M7 5.75C5.20507 5.75 3.75 7.20507 3.75 9V15C3.75 16.7949 5.20507 18.25 7 18.25H17C18.7949 18.25 20.25 16.7949 20.25 15V9C20.25 7.20507 18.7949 5.75 17 5.75H7ZM2.25 9C2.25 6.37665 4.37665 4.25 7 4.25H17C19.6234 4.25 21.75 6.37665 21.75 9V15C21.75 17.6234 19.6234 19.75 17 19.75H7C4.37665 19.75 2.25 17.6234 2.25 15V9ZM9.63048 8.34735C9.86561 8.21422 10.1542 8.21786 10.3859 8.35688L15.3859 11.3569C15.6118 11.4924 15.75 11.7366 15.75 12C15.75 12.2634 15.6118 12.5076 15.3859 12.6431L10.3859 15.6431C10.1542 15.7821 9.86561 15.7858 9.63048 15.6526C9.39534 15.5195 9.25 15.2702 9.25 15V9C9.25 8.7298 9.39534 8.48048 9.63048 8.34735ZM10.75 10.3246V13.6754L13.5423 12L10.75 10.3246Z" fill="black" fillOpacity="1" />
		</SvgIcon>
	);
};

const StyledYoutubeIcon = styled( YoutubeIcon )( ( { theme } ) => ( {
	'& path': {
		fill: theme.palette.text.secondary
		,
	},
} ) );

export default StyledYoutubeIcon;
