/**
 * Role Store - Created using shared store factory pattern
 *
 * This replaces 500+ lines of duplicated code with just 3 lines!
 * The createCrudStore factory provides all CRUD functionality automatically.
 */
import { createCrudStore } from "@/shared/store/createStore";
import setup from "../setup";

// Create the store configuration
const useRoleStore = createCrudStore(setup);

// For backwards compatibility with mapActions pattern
export const store = useRoleStore;

export default useRoleStore;
