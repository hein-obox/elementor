import * as React from 'react';
import { SvgIcon, SvgIconProps } from '@elementor/ui';

const ArchiveTemplateIcon = React.forwardRef( ( props: SvgIconProps, ref ) => {
	return (
		<SvgIcon viewBox="0 0 24 24" { ...props } ref={ ref }>
			<path fillRule="evenodd" clipRule="evenodd" d="M3.25 4.5C3.25 4.08579 3.58579 3.75 4 3.75H10C10.4142 3.75 10.75 4.08579 10.75 4.5V12C10.75 12.4142 10.4142 12.75 10 12.75H4C3.58579 12.75 3.25 12.4142 3.25 12V4.5ZM4.75 5.25V11.25H9.25V5.25H4.75ZM13.25 4.5C13.25 4.08579 13.5858 3.75 14 3.75H20C20.4142 3.75 20.75 4.08579 20.75 4.5V12C20.75 12.4142 20.4142 12.75 20 12.75H14C13.5858 12.75 13.25 12.4142 13.25 12V4.5ZM14.75 5.25V11.25H19.25V5.25H14.75ZM3.25 16C3.25 15.5858 3.58579 15.25 4 15.25H10C10.4142 15.25 10.75 15.5858 10.75 16C10.75 16.4142 10.4142 16.75 10 16.75H4C3.58579 16.75 3.25 16.4142 3.25 16ZM13.25 16C13.25 15.5858 13.5858 15.25 14 15.25H20C20.4142 15.25 20.75 15.5858 20.75 16C20.75 16.4142 20.4142 16.75 20 16.75H14C13.5858 16.75 13.25 16.4142 13.25 16ZM3.25 20C3.25 19.5858 3.58579 19.25 4 19.25H10C10.4142 19.25 10.75 19.5858 10.75 20C10.75 20.4142 10.4142 20.75 10 20.75H4C3.58579 20.75 3.25 20.4142 3.25 20ZM13.25 20C13.25 19.5858 13.5858 19.25 14 19.25H20C20.4142 19.25 20.75 19.5858 20.75 20C20.75 20.4142 20.4142 20.75 20 20.75H14C13.5858 20.75 13.25 20.4142 13.25 20Z" />
		</SvgIcon>
	);
} );

export default ArchiveTemplateIcon;