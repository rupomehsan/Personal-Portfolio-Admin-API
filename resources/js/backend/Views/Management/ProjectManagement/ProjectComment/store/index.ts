import { createCrudStore } from "@/shared/store/createStore";
import setup from "../setup";

export const project_comment_store = createCrudStore(setup);
export const store = project_comment_store;
export default project_comment_store;
