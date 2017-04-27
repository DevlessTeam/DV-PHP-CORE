<?php

/**
 * Description of ContentApi
 *
 * @author Arsen Tochemey GANDOTE
 */
class ContentApi extends AbstractApi {

    public function __construct($apiHost, $enableConsoleLog = TRUE) {
        parent::__construct($apiHost, $enableConsoleLog);
    }

    /**
     * Fetches a paginated list of content libraries related to a particular account.
     * It will return an ApiList of ContentLibrary object when successful.
     * @param integer $page The page index
     * @param integer $pageSize The number of items on a page
     * @return ApiList|HttpResponse|null
     * @throws Exception
     */
    public function getContentLibraries($page = -1, $pageSize = -1) {
        $resource = "/libraries/";
        try {
            $params = array();
            if (is_int($page) && $page > 0) {
                $params["Page"] = $page;
            }

            if (is_int($pageSize) && $pageSize > 0) {
                $params["PageSize"] = $pageSize;
            }

            $response = $this->httpClient->get($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new ApiList($json);
                    }
                } else {
                    return $response;
                }
            }
        } catch (Exception $ex) {
            $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Fetches the details of a content library. It will return a ContentLibrary object when successful.
     * @param UUID $libraryId The content library id
     * @return ContentLibrary|HttpResponse|null
     * @throws ErrorException
     */
    public function getContentLibrary($libraryId) {
        $resource = "/libraries/";
        if (is_null($libraryId)) {
            throw new ErrorException("Parameter 'libraryId' cannot be null");
        } elseif (!CommonUtil::is_uuid($libraryId)) {
            throw new ErrorException("Parameter 'libraryId' must be a valid UUID");
        }

        try {
            $resource .= $libraryId;
            $response = $this->httpClient->get($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new ContentLibrary($json);
                    }
                } else {
                    return $response;
                }
            }
        } catch (Exception $ex) {
            $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Adds a new content library. It will return a ContentLibrary object when successful.
     * @param string $name The content library name
     * @param string $shortName The content library shortname.
     * @return ContentLibrary|HttpResponse|null
     * @throws ErrorException
     */
    public function addContentLibrary($name, $shortName) {
        $resource = "/libraries/";
        if (is_null($name)) {
            throw new ErrorException("Parameter 'name' cannot be null");
        } else if (!is_string($name)) {
            throw new ErrorException("Parameter 'name' must be a string");
        }

        if (is_null($shortName)) {
            $shortName = $name;
        } else if (!is_string($shortName)) {
            $shortName = $name;
        }

        try {
            $params = array();
            $params["Name"] = $name;
            $params["ShortName"] = $shortName;
            $response = $this->httpClient->post($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_CREATED) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new ContentLibrary($json);
                    }
                } else {
                    return $response;
                }
            }
        } catch (Exception $ex) {
            $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Updates the details of an existing content library. It will return a ContentLibrary object when successful.
     * @param UUID $libraryId The content library id
     * @param mixed $request associative array containing the fields to update for the given content library.
     * The possible keys for this array are: 
     *     - Name
     *     - ShortName
     * @return ContentLibrary|HttpResponse|null
     * @throws ErrorException
     */
    public function updateContentLibrary($libraryId, array $request = array()) {
        $resource = "/libraries/";
        if (is_null($request)) {
            throw new ErrorException("Parameter 'request' cannot be null");
        } else if (!is_array($request)) {
            throw new ErrorException("Parameter 'request' must be an array");
        } else {
            // Let us check the required keys exist
            if (!array_key_exists("Name", $request) && !array_key_exists("ShortName", $request)) {
                throw new ErrorException("Parameter 'request' must be an array with the required keys.");
            }
        }

        if (is_null($libraryId)) {
            throw new ErrorException("Parameter 'libraryId' cannot be null");
        } elseif (!CommonUtil::is_uuid($libraryId)) {
            throw new ErrorException("Parameter 'libraryId' must be a UUID");
        }

        try {
            $resource .= $libraryId;
            $response = $this->httpClient->put($resource, $request);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new ContentLibrary($json);
                    }
                } else {
                    return $response;
                }
            }
        } catch (Exception $ex) {
            $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Deletes an existing content library. It will return true when successful.
     * @param UUID $libraryId The content library Id
     * @return HttpResponse|boolean
     * @throws ErrorException
     */
    public function deleteContentLibrary($libraryId) {
        $resource = "/libraries/";
        if (is_null($libraryId)) {
            throw new ErrorException("Parameter 'libraryId' cannot be null");
        } elseif (!CommonUtil::is_uuid($libraryId)) {
            throw new ErrorException("Parameter 'libraryId' must be a valid UUID");
        }

        try {
            $resource .= $libraryId;
            $response = $this->httpClient->delete($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_NO_CONTENT) {
                    return TRUE;
                } else {
                    return $response;
                }
            }
        } catch (Exception $ex) {
            $ex->getTraceAsString();
        }
        return FALSE;
    }

    /**
     * Fetches a paginated list of the content folders. It will return an ApiList of ContentFolder object when successful.
     * @param integer $page The page number
     * @param integer $pageSize The number of item on a page
     * @return ApiList|HttpResponse|null
     */
    public function getContentFolders($page = -1, $pageSize = -1) {
        $resource = "/folders/";
        try {
            $params = array();
            if (is_int($page) && $page > 0) {
                $params["Page"] = $page;
            }

            if (is_int($pageSize) && $pageSize > 0) {
                $params["PageSize"] = $pageSize;
            }

            $response = $this->httpClient->get($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new ApiList($json);
                    }
                } else {
                    return $response;
                }
            }
        } catch (Exception $ex) {
            $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Fetches the details of a given content folder. It will return a ContentFolder object when successful.
     * @param integer $folderId The content folder id.
     * @return HttpResponse|ContentFolder|null
     * @throws ErrorException
     */
    public function getContentFolder($folderId) {
        $resource = "/folders/";
        if (is_null($folderId)) {
            throw new ErrorException("Parameter 'folderId' cannot be null");
        } elseif (!is_int($folderId)) {
            throw new ErrorException("Parameter 'folderId' must be an integer");
        }

        try {
            $resource .= $folderId;
            $response = $this->httpClient->get($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json))
                        return new ContentFolder($json);
                } else
                    return $response;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return null;
    }

    /**
     * Creates a new content folder. It will return a ContentFolder object when successful.
     * @param string $folderName The content folder name
     * @param UUID $libraryId The content library Id
     * @param mixed $parentFolder The content parent folder. This parameter can have the possible value types:
     *      - integer. Stands for the content parent folder id
     *      - string . Stands for the content parent folder absolute path.
     * @return HttpResponse|ContentFolder|null
     * @throws ErrorException
     */
    public function addContentFolder($folderName, $libraryId, $parentFolder = null) {
        $resource = "/folders/";
        if (is_null($folderName)) {
            throw new ErrorException("Parameter 'folderName' cannot be null");
        } else if (!is_string($folderName)) {
            throw new ErrorException("Parameter 'folderName' must be a string");
        }

        if (is_null($libraryId)) {
            throw new ErrorException("Parameter 'libraryId' cannot be null");
        } elseif (!CommonUtil::is_uuid($libraryId)) {
            throw new ErrorException("Parameter 'libraryId' must be a valid UUID");
        }

        try {
            $params = array();
            $params["FolderName"] = $folderName;
            $params["LibraryId"] = $libraryId;
            $params["Parent"] = $parentFolder;
            $response = $this->httpClient->post($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_CREATED) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new ContentFolder($json);
                    }
                } else {
                    return $response;
                }
            }
        } catch (Exception $ex) {
            $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Updates the details of a given content folder. It will return a ContentFolder object when successful.
     * @param integer $folderId The content folder id 
     * @param string $folderName The new content folder name
     * @param UUID $libraryId The new content library id
     * @param mixed $parentFolder The new content parent folder.This parameter can have the possible value types:
     *      - integer. Stands for the content parent folder id
     *      - string . Stands for the content parent folder absolute path.
     * @return HttpResponse|ContentFolder|null
     * @throws ErrorException
     */
    public function updateContentFolder($folderId, $folderName = null, $libraryId = null, $parentFolder = null) {
        $resource = "/folders/";
        if (is_null($folderId)) {
            throw new ErrorException("Parameter 'folderId' cannot be null");
        } elseif (!is_int($folderId)) {
            throw new ErrorException("Parameter 'folderId' must be an integer");
        }

        if (!is_null($folderName) && empty($folderName)) {
            throw new ErrorException("Parameter 'folderName' must be a string");
        }

        if (!is_null($libraryId) && !CommonUtil::is_uuid($libraryId)) {
            throw new ErrorException("Parameter 'libraryId' must be a valid UUID");
        }

        try {
            $resource .= $folderId;
            $params = array();
            $params["FolderName"] = $folderName;
            $params["LibraryId"] = $libraryId;
            $params["Parent"] = $parentFolder;
            $response = $this->httpClient->put($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new ContentFolder($json);
                    }
                } else {
                    return $response;
                }
            }
        } catch (Exception $ex) {
            $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Deletes an existing content folder
     * It will return true when successful.
     * @param integer $folderId The content folder id
     * @return HttpResponse|boolean
     * @throws ErrorException
     */
    public function deleteContentFolder($folderId) {
        $resource = "/folders/";
        if (is_null($folderId)) {
            throw new ErrorException("Parameter 'folderId' cannot be null");
        } elseif (!is_int($folderId)) {
            throw new ErrorException("Parameter 'folderId' must be an integer");
        }

        try {
            $resource .= $folderId;
            $response = $this->httpClient->delete($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_NO_CONTENT) {
                    return TRUE;
                } else {
                    return $response;
                }
            }
        } catch (Exception $ex) {
            $ex->getTraceAsString();
        }
        return FALSE;
    }

    /**
     * Fetches a paginated list of content medias with additional query string parameters as filters.
     * It will return an ApiList of  ContentMedia object when successful.
     * @param integer $page The page number
     * @param integer $pageSize The number of items on a page
     * @param array $filters additional query string parameters
     * @return ApiList|HttpResponse|null
     */
    public function getContentMedias($page = -1, $pageSize = -1, $filters = array()) {
        $resource = "/media/";
        try {
            $params = array();
            if (is_int($page) && $page > 0) {
                $params["Page"] = $page;
            }

            if (is_int($pageSize) && $pageSize > 0) {
                $params["PageSize"] = $pageSize;
            }

            if (!is_null($filters) && count($filters) > 0) {
                foreach ($filters as $key => $value) {
                    $params[$key] = $value;
                }
            }

            $response = $this->httpClient->get($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new ApiList($json);
                    }
                } else {
                    return $response;
                }
            }
        } catch (Exception $ex) {
            $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Fetches the details of a given content media. It will return a ContentMedia object when successful.
     * @param UUID $mediaId The content media id.
     * @return HttpResponse|ContentMedia|null
     * @throws ErrorException
     */
    public function getContentMedia($mediaId) {
        $resource = "/media/";
        if (is_null($mediaId)) {
            throw new ErrorException("Parameter 'mediaId' cannot be null");
        } elseif (!CommonUtil::is_uuid($mediaId)) {
            throw new ErrorException("Parameter 'mediaId' must be a valid UUID");
        }

        try {
            $resource .= $mediaId;
            $response = $this->httpClient->get($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new ContentMedia($json);
                    }
                } else {
                    return $response;
                }
            }
        } catch (Exception $ex) {
            $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Deletes an existing content media. It will return true when successful.
     * @param UUID $mediaId
     * @return HttpResponse|boolean
     * @throws ErrorException
     */
    public function deleteContentMedia($mediaId) {
        $resource = "/media/";
        if (is_null($mediaId)) {
            throw new ErrorException("Parameter 'mediaId' cannot be null");
        } elseif (!CommonUtil::is_uuid($mediaId)) {
            throw new ErrorException("Parameter 'mediaId' must be a valid UUID");
        }

        try {
            $resource .= $mediaId;
            $response = $this->httpClient->delete($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_NO_CONTENT) {
                    return TRUE;
                } else {
                    return $response;
                }
            }
        } catch (Exception $ex) {
            $ex->getTraceAsString();
        }
        return FALSE;
    }

    /**
     *  Uploads a new content media file with content media metadata
     * @param string $filePath The file to upload.
     * @param MediaInfo $mediaInfo The file metadata
     * @return HttpResponse|ContentMedia|null
     * @throws ErrorException
     */
    public function addContentMedia($mediaInfo, $filePath = null) {
        $resource = "/media/";
        $fileUpload = true;
        if (!is_null($filePath)) {
            if (file_exists($filePath)) {
                throw new ErrorException("Parameter 'filePath' cannot be null");
            }
        } else {
            $fileUpload = false;
        }

        if (is_null($mediaInfo)) {
            throw new ErrorException("Parameter 'mediaInfo' cannot be null");
        } elseif (!($mediaInfo instanceof MediaInfo)) {
            throw new ErrorException("Parameter 'mediaInfo' must be an instance.");
        }

        try {
            $params = array();
            if ($mediaInfo instanceof MediaInfo) {
                // Set the mediainfo data
                $params["ContentName"] = $mediaInfo->contentName;
                $params["LibraryId"] = $mediaInfo->libraryId;
                $params["DestinationFolder"] = $mediaInfo->destinationFolder;
                $params["Preference"] = $mediaInfo->preference;
                $params["Width"] = $mediaInfo->width;
                $params["Height"] = $mediaInfo->height;
                $params["DrmProtect"] = $mediaInfo->drmProtect;
                $params["Streamable"] = $mediaInfo->streamable;
                $params["DisplayText"] = $mediaInfo->displayText;
                $params["ContentText"] = $mediaInfo->contentText;

                $params["Tags"] = "";

                // set the medainfo tags
                if (!is_null($mediaInfo->tags) && is_array($mediaInfo->tags) && count($mediaInfo->tags) > 0) {
                    $params["Tags"] = JsonHelper::toJson($mediaInfo->tags);
                }

                $response = null;
                // check if it is a file upload
                if ($fileUpload) {
                    $file = array();
                    $file["MediaFile"] = $filePath;
                    $response = $this->httpClient->postFiles($resource, "application/json", $params, $file);
                } else {
                    $response = $this->httpClient->post($resource, $params);
                }

                if ($response != null && $response instanceof HttpResponse) {
                    if ($response->getStatus() === HttpStatusCode::HTTP_CREATED) {
                        $json = JsonHelper::getJson($response->getBody());
                        if (isset($json)) {
                            return new ContentMedia($json);
                        }
                    } else {
                        return $response;
                    }
                }
            }
        } catch (Exception $ex) {
            $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Updates the details of an existing content media.
     * @param UUID $mediaId The content media
     * @param MediaInfo $mediaInfo The content media details
     * @return HttpResponse|ContentMedia|null
     * @throws ErrorException
     */
    public function updateContentMedia($mediaId, $mediaInfo = null) {
        $resource = "/media/";
        if (is_null($mediaId)) {
            throw new ErrorException("Parameter 'mediaId' cannot be null");
        } elseif (!CommonUtil::is_uuid($mediaId)) {
            throw new ErrorException("Parameter 'mediaId' must be a valid UUID");
        }

        if (!is_null($mediaInfo) && !($mediaInfo instanceof MediaInfo)) {
            throw new ErrorException("Parameter 'mediaInfo' cannot be null and must be an instance of MediaInfo.");
        }

        try {
            $resource .= $mediaId;
            // Set the mediainfo data
            $params["ContentName"] = $mediaInfo->contentName;
            $params["LibraryId"] = $mediaInfo->libraryId;
            $params["DestinationFolder"] = $mediaInfo->destinationFolder;
            $params["Preference"] = $mediaInfo->preference;
            $params["Width"] = $mediaInfo->width;
            $params["Height"] = $mediaInfo->height;
            $params["DrmProtect"] = $mediaInfo->drmProtect;
            $params["Streamable"] = $mediaInfo->streamable;
            $params["DisplayText"] = $mediaInfo->displayText;
            $params["ContentText"] = $mediaInfo->contentText;

            $params["Tags"] = "";

            // set the medainfo tags
            if (!is_null($mediaInfo->tags) && is_array($mediaInfo->tags) && count($mediaInfo->tags) > 0) {
                $params["Tags"] = JsonHelper::toJson($mediaInfo->tags);
            }

            $response = $this->httpClient->put($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new ContentMedia($json);
                    }
                } else {
                    return $response;
                }
            }
        } catch (Exception $ex) {
            $ex->getTraceAsString();
        }
        return null;
    }

}
