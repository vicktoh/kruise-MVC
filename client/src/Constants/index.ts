export const BASE_URL =
  window.location.hostname === "localhost" ? "/dataphyte_search/" : "";

export const LOGIN_STATUS_URL = `${BASE_URL}/Login/is_logged_in/`;
export const BACK_TABLE_DATA_URL = `${BASE_URL}/Data/fetch`;
export const FETCH_PLACES_URL = `${BASE_URL}/Data/fetch_places`;
export const FETCH_CATEGORIES_URL = `${BASE_URL}/Data/fetch_categories`;
export const PUT_DATA_URL = `${BASE_URL}/Data/put_data`;
export const PUT_CATEGORY_URL = `${BASE_URL}/Data/put_category`;
export const PUT_PLACE_URL = `${BASE_URL}/Data/put_place`;
export const UPDATE_DATA_URL = `${BASE_URL}/Data/update_data/`;
export const UPDATE_CATEGORY_URL = `${BASE_URL}/Data/update_category/`;
export const UPDATE_PLACE_URL = `${BASE_URL}/Data/update_place/`;
export const DELETE_ENTITY_URL = `${BASE_URL}/Data/delete/`;