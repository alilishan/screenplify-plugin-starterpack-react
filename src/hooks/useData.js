import { useEffect, useReducer } from "react";


const useData = (component_id = "") => {
    const [state, setState] = useReducer((state, newState) => ({
        ...state,
        ...newState
    }), {
        dataLoaded: false,
        data: {}
    });

    useEffect(() => {
        const getData = async () => {
            const response = await fetch(`${process.env.PUBLIC_URL}/data.json?ts=${new Date().getTime()}`)
            const data = await response.json();

            setState({
                data: component_id in data ? { ...data.defaults, ...data[component_id] } : { ...data.defaults },
                dataLoaded: true
            });
        }

        getData();

    }, [component_id]);

    return { ...state };
}

export default useData;