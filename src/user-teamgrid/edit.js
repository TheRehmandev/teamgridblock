import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, Placeholder, Spinner } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { useSelect } from '@wordpress/data';
import './editor.scss';

export default function Edit( { attributes, setAttributes } ) {
    const { companyId = 0, departmentId = 0 } = attributes;
    const blockProps = useBlockProps();

    const { companies, isLoadingCompanies } = useSelect( ( select ) => {
        const { getEntityRecords, isResolving } = select( 'core' );
        return {
            companies: getEntityRecords( 'postType', 'company', { per_page: -1, status: 'publish', orderby: 'title', order: 'asc' } ),
            isLoadingCompanies: isResolving( 'getEntityRecords', [ 'postType', 'company', { per_page: -1, status: 'publish' } ] ),
        };
    }, [] );

    const { departments, isLoadingDepartments } = useSelect( ( select ) => {
        const { getEntityRecords, isResolving } = select( 'core' );
        const query = { per_page: -1, status: 'publish', orderby: 'title', order: 'asc' };
        if ( companyId ) {
            query.meta_key = '_linked_company';
            query.meta_value = companyId;
        }
        return {
            departments: getEntityRecords( 'postType', 'department', query ),
            isLoadingDepartments: isResolving( 'getEntityRecords', [ 'postType', 'department', query ] ),
        };
    }, [ companyId ] );

    const companyOptions = [ { label: '— Select Company —', value: 0 } ];
    if ( Array.isArray( companies ) ) {
        companies.forEach( ( company ) => {
            companyOptions.push( { label: company?.title?.rendered || '(no title)', value: company.id } );
        } );
    }

    const departmentOptions = [ { label: '— Select Department —', value: 0 } ];
    if ( Array.isArray( departments ) ) {
        departments.forEach( ( department ) => {
            departmentOptions.push( { label: department?.title?.rendered || '(no title)', value: department.id } );
        } );
    }

    return (
        <div { ...blockProps }>
            <InspectorControls>
                <PanelBody title="Team Grid Settings" initialOpen={ true }>
                    <SelectControl
                        label="Company"
                        value={ companyId }
                        options={ companyOptions }
                        onChange={ ( val ) => {
                            const newCompanyId = Number( val );
                            setAttributes( { companyId: newCompanyId, departmentId: 0 } );
                        } }
                        help="Select a company to filter team members"
                    />
                    { isLoadingCompanies && <Spinner /> }

                    <SelectControl
                        label="Department"
                        value={ departmentId }
                        options={ departmentOptions }
                        onChange={ ( val ) => setAttributes( { departmentId: Number( val ) } ) }
                        disabled={ ! companyId }
                        help={ ! companyId ? 'Select a company first' : 'Select a department to filter team members' }
                    />
                    { isLoadingDepartments && <Spinner /> }
                </PanelBody>
            </InspectorControls>

            { companyId && departmentId ? (
                <ServerSideRender block="usercompanygrid/team-grid" attributes={ { companyId, departmentId } } />
            ) : (
                <Placeholder icon="groups" label="Team Grid" instructions="Select a company and department from the block settings in the sidebar to display team members.">
                    { ! companyId && <p><strong>Step 1:</strong> Select a company</p> }
                    { companyId && ! departmentId && <p><strong>Step 2:</strong> Select a department</p> }
                </Placeholder>
            ) }
        </div>
    );
}
