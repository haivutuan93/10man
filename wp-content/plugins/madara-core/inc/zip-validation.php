<?php

    class MADARA_ZIP_VALIDATION{

        static function is_zip_valid( $zip_file, $chapter_type ){

            $zip = self::get_zip_structure( $zip_file );

            $is_valid = false;

            $chapter_type = $chapter_type == 'text' || $chapter_type == 'video' ? 'content' : 'manga';

            if( $chapter_type == $zip['chapter_type'] ){
                $is_valid = true;
            }

            if( ! $is_valid ){
                return array(
                    'is_valid' => false,
                    'message' => esc_html__("This Zip file isn't valid for this chapter type"),
                    'data' => $zip
                );
            }

            return array(
                'is_valid' => true,
                'data' => $zip,
            );

        }

        //this function would read the zip file to manga structure : volume > chapter > chapter file
        static function get_zip_structure( $zip_file ){

            $zip = zip_open( $zip_file );

            $zip_structure = array();

            while( $zip_entry = zip_read( $zip ) ){

                $entry_name = zip_entry_name( $zip_entry );

                //check if this is a dir or a file
                $is_zip_dir = self::is_zip_dir( $entry_name );

                //if this zip hasn't defined if it's a single chapter zip or it was a single chapter zip
                if( !isset( $is_single_chapter ) || $is_single_chapter ){

                    //check if this entry is a file
                    $extension = self::get_file_extension( $entry_name );

                    if( $extension ){ //if it's a file, it's would be a manga single chapter zip file
                        $zip_structure[] = array(
                            'single_chapter_file' => basename( $entry_name ),
                            'extension'           => $extension
                        );
                        $is_single_chapter = true;

                        //get chapter type for single chapter zip file
                        $images_extensions = array( 'jpg', 'jpeg', 'bmp', 'png', 'gif' );

                        if( in_array( strtolower( $extension ), $images_extensions ) ){
                            $chapter_type = 'manga';
                        }else{
                            $chapter_type = 'content';
                        }

                    }else{
                        $is_single_chapter = false;
                    }

                    if( $is_single_chapter ){
                        continue;
                    }
                }else{
                    $is_single_chapter = false;
                }

                //trim the / on dir name
                $entry_name = trim($entry_name, '/');

                //if it's not single_chapter file type
                $parts = explode('/', $entry_name);

                if( !$is_zip_dir ){ //if it's file

                    //by this, file path will locate at key 0
                    $parts = array_reverse( $parts );

                    $extension = self::get_file_extension( $parts[0] );

                    $images_extensions = array( 'jpg', 'jpeg', 'bmp', 'png', 'gif' );

                    if( in_array( strtolower( $extension ), $images_extensions ) ){
                        $chapter_type = 'manga';
                    }else{
                        $chapter_type = 'content';
                    }

                    if( count( $parts ) == 3 ){
                        $zip_structure[$parts[2]][$parts[1]][] = array(
                            'file' => $parts[0],
                            'extension' => $extension
                        );
                        $zip_type = 'multi_chapters_with_volumes';
                    }elseif( count( $parts ) == 2 ){
                        $zip_structure[$parts[1]][] = array(
                            'file' => $parts[0],
                            'extension' => $extension
                        );
                    }
                }

            }

            zip_close( $zip );

            /*
            *   There will be 3 types of zip file
            *       - single_chapter : only contains images file
            *       - multi_chapters_with_volumes : contains volumes -> chapters -> images file
            *       - multi_chapters_no_volume : contains chapters -> images file
            */
            if( $is_single_chapter ){
                $zip_type = 'single_chapter';
                $chapter_type = 'manga';
            }elseif( !isset( $zip_type ) ){
                $zip_type = 'multi_chapters_no_volume';
            }

            return array(
                'zip_type'     => $zip_type,
                'chapter_type' => $chapter_type,
                'data'         => $zip_structure
            );
        }

        static function get_file_extension( $filename ){

            $pathinfo = pathinfo( $filename );

            if( isset( $pathinfo['extension'] ) ){
                return $pathinfo['extension'];
            }

            return false;

        }

        static function is_zip_dir( $filename ){

            return substr( $filename, -1 ) === '/';

        }
    }

    $madara_zip_validation = new MADARA_ZIP_VALIDATION();
