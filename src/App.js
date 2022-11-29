import React, { useEffect, useReducer } from 'react';
import Spinner from './components/Spinner';
import useData from './hooks/useData';

const App = () => {
    const params = new URLSearchParams(window.location.search);
	const componentId = params.has('component_id') ? params.get('component_id') : null;
	const { dataLoaded, data } = useData(componentId);

	const [state, setState] = useReducer((state, newState) => ({
		...state,
		...newState
	}), {
		isLoading: true,
		isError: false,
		errorMessage: '',
		items: []
	});

    useEffect(() => {
        if(!dataLoaded) setState({ isLoading: false });
    }, [dataLoaded])

	if(state.isLoading){
		return (
			<div className="position-fixed d-flex align-items-center justify-content-center w-100 h-100" style={{top: '0', left: '0'}}>
				<Spinner />
			</div>
		)
	}

	if(state.isError){
		return (
			<div className="position-fixed d-flex align-items-center justify-content-center w-100 h-100 flex-column" style={{top: '0', left: '0'}}>
				<h4 className="text-danger m-2">There was an error getting data from RSS feed.</h4>
				<small>[{state.errorMessage}]</small>
			</div>
		)
	}

    return (
        <div>
            <h1>App</h1>
            <pre>{JSON.stringify(data, ' ', 4)}</pre>
        </div>
    );
}

export default App;
