/**
 * Converts strings 'true' and 'false' to boolen type
 * 
 * @param boolStr The string to be converted
 * 
 * @returns `boolean` 'true' or 'false'
 * 
 * @throws `TypeError` throws error if a string other than 'true' or 'false' is given
 * 
 * @example ```ts
 * let verified: any = 'false';
 * console.log(typeof verified);
 * // prints: string;
 * verified = strToBool('false');
 * console.log(typeof verified);
 * // prints: boolean;
 * ```
 * @author HayKay
 */
export const strToBool = (boolStr: string): boolean => {
    if (boolStr === 'true') {
        return true;
    } else if (boolStr === 'false') {
        return false;
    } else {
        throw new TypeError("Invalid boolean name. Expected 'true'/'false', found '" + boolStr + "'");
    }
}